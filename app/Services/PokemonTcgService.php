<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PokemonTcgService
{
    private string $baseUrl = 'https://api.tcgdex.net/v2/en';

    /**
     * Make a GET request to the TCGdex API.
     */
    private function request(string $path): ?array
    {
        try {
            $response = Http::connectTimeout(10)
                ->timeout(20)
                ->get("{$this->baseUrl}{$path}");

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Normalize a set from TCGdex format to the app's expected format.
     *
     * Ensures views can keep using $set['images']['logo'], $set['series'],
     * $set['printedTotal'], $set['releaseDate'] without any changes.
     */
    private function normalizeSet(array $raw, ?string $serieName = null, ?string $releaseDate = null): array
    {
        // Build symbol URL: prefer explicit, else construct from serie path
        $symbolUrl = '';
        if (isset($raw['symbol'])) {
            $symbolUrl = $raw['symbol'] . '.png';
        } elseif (isset($raw['serie']['id'])) {
            // Construct from known TCGdex asset pattern
            $symbolUrl = "https://assets.tcgdex.net/univ/{$raw['serie']['id']}/{$raw['id']}/symbol.png";
        }

        return [
            'id'           => $raw['id'] ?? '',
            'name'         => $raw['name'] ?? '',
            'images'       => [
                'logo'   => isset($raw['logo']) ? $raw['logo'] . '.png' : '',
                'symbol' => $symbolUrl,
            ],
            'series'       => $serieName ?? $raw['serie']['name'] ?? 'Unknown',
            'releaseDate'  => $releaseDate ?? $raw['releaseDate'] ?? '1999-01-01',
            'printedTotal' => $raw['cardCount']['official'] ?? $raw['cardCount']['total'] ?? 0,
            'cardCount'    => $raw['cardCount'] ?? ['total' => 0, 'official' => 0],
        ];
    }

    /**
     * Normalize a card from TCGdex format to the app's expected format.
     *
     * Maps: category→supertype, illustrator→artist, localId→number,
     * image→images.small/large so views need zero changes.
     */
    private function normalizeCard(array $raw): array
    {
        $supertype = match ($raw['category'] ?? 'Other') {
            'Pokemon' => 'Pokémon',
            default   => $raw['category'] ?? 'Other',
        };

        $subtypes = array_values(array_filter([
            $raw['stage'] ?? null,
            $raw['suffix'] ?? null,
            $raw['trainerType'] ?? null,
        ]));

        return [
            'id'        => $raw['id'] ?? '',
            'name'      => $raw['name'] ?? '',
            'supertype' => $supertype,
            'subtypes'  => $subtypes,
            'types'     => $raw['types'] ?? [],
            'hp'        => $raw['hp'] ?? null,
            'rarity'    => $raw['rarity'] ?? null,
            'artist'    => $raw['illustrator'] ?? null,
            'number'    => $raw['localId'] ?? '',
            'images'    => [
                'small' => isset($raw['image']) ? $raw['image'] . '/low.webp' : '',
                'large' => isset($raw['image']) ? $raw['image'] . '/high.webp' : '',
            ],
        ];
    }

    /**
     * Get all sets ordered by release date (newest first).
     *
     * Fetches all series concurrently, flattens their sets,
     * attaches series name + release date to each set.
     */
    public function getAllSets(): array
    {
        $result = Cache::remember('tcgdex_all_sets', 3600, function () {
            $seriesList = $this->request('/series');
            if (!$seriesList || !is_array($seriesList)) {
                return null;
            }

            // Fetch every series detail concurrently
            $responses = Http::pool(fn (Pool $pool) =>
                array_map(
                    fn ($s) => $pool->connectTimeout(10)->timeout(20)
                        ->get("{$this->baseUrl}/series/{$s['id']}"),
                    $seriesList
                )
            );

            $allSets = [];
            $order   = 0;

            foreach ($responses as $response) {
                if (! ($response instanceof \Illuminate\Http\Client\Response) || $response->failed()) {
                    continue;
                }

                $series = $response->json();
                if (!$series || !isset($series['sets'])) {
                    continue;
                }

                $serieName = $series['name'] ?? 'Unknown';
                $serieDate = $series['releaseDate'] ?? '1999-01-01';

                foreach ($series['sets'] as $set) {
                    $normalized           = $this->normalizeSet($set, $serieName, $serieDate);
                    $normalized['_order'] = $order++;
                    $allSets[]            = $normalized;
                }
            }

            // Sort newest first (by series date, then by position within series)
            usort($allSets, function ($a, $b) {
                $cmp = strcmp($b['releaseDate'], $a['releaseDate']);
                return $cmp !== 0 ? $cmp : $b['_order'] - $a['_order'];
            });

            // Strip internal sort key
            return array_map(fn ($s) => array_diff_key($s, ['_order' => true]), $allSets);
        });

        if (empty($result)) {
            Cache::forget('tcgdex_all_sets');
            return [];
        }

        return $result;
    }

    /**
     * Get a single set by ID.
     */
    public function getSet(string $id): ?array
    {
        $result = Cache::remember("tcgdex_set_{$id}", 3600, function () use ($id) {
            $data = $this->request("/sets/{$id}");
            return $data ? $this->normalizeSet($data) : null;
        });

        if ($result === null) {
            Cache::forget("tcgdex_set_{$id}");
        }

        return $result;
    }

    /**
     * Get cards for a set with full details (category, rarity, etc.).
     *
     * Fetches the card list from /sets/{id}, then fetches each card's
     * detail concurrently in batches of 30 for grouping & popup data.
     */
    public function getSetCards(string $setId, int $page = 1, int $pageSize = 250): array
    {
        $cacheKey = "tcgdex_cards_{$setId}";

        $result = Cache::remember($cacheKey, 3600, function () use ($setId) {
            $setData = $this->request("/sets/{$setId}");
            if (!$setData || !isset($setData['cards']) || empty($setData['cards'])) {
                return null;
            }

            $allCards = [];
            $chunks   = array_chunk($setData['cards'], 30);

            foreach ($chunks as $chunk) {
                $responses = Http::pool(fn (Pool $pool) =>
                    array_map(
                        fn ($c) => $pool->connectTimeout(10)->timeout(20)
                            ->get("{$this->baseUrl}/cards/{$c['id']}"),
                        $chunk
                    )
                );

                foreach ($responses as $response) {
                    if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                        $card = $response->json();
                        if ($card) {
                            $allCards[] = $this->normalizeCard($card);
                        }
                    }
                }
            }

            // Sort by card number
            usort($allCards, function ($a, $b) {
                $numA = $a['number'];
                $numB = $b['number'];
                if (is_numeric($numA) && is_numeric($numB)) {
                    return (int) $numA - (int) $numB;
                }
                return strcmp($numA, $numB);
            });

            return $allCards;
        });

        if (empty($result)) {
            Cache::forget($cacheKey);
            return [];
        }

        return $result;
    }

    /**
     * Get a single card.
     */
    public function getCard(string $id): ?array
    {
        return Cache::remember("tcgdex_card_{$id}", 3600, function () use ($id) {
            $data = $this->request("/cards/{$id}");
            return $data ? $this->normalizeCard($data) : null;
        });
    }

    /**
     * Classify a rarity string into a tier using pattern matching.
     *
     * Works with any API's rarity naming convention.
     */
    private function getRarityTier(string $rarity): string
    {
        $lower = strtolower($rarity);

        if (str_contains($lower, 'secret') || str_contains($lower, 'rainbow')
            || str_contains($lower, 'hyper')
            || (str_contains($lower, 'shiny') && str_contains($lower, 'ultra'))) {
            return 'secret';
        }
        if (str_contains($lower, 'illustration') || str_contains($lower, 'amazing')) {
            return 'illustration';
        }
        if (str_contains($lower, 'ultra') || str_contains($lower, 'double')
            || str_contains($lower, 'ace') || str_contains($lower, ' ex')
            || str_contains($lower, ' gx') || str_contains($lower, 'vmax')
            || str_contains($lower, 'vstar') || str_contains($lower, ' v ')) {
            return 'ultra';
        }
        if (str_contains($lower, 'rare') || str_contains($lower, 'holo')) {
            return 'rare';
        }
        if (str_contains($lower, 'uncommon')) {
            return 'uncommon';
        }

        return 'common';
    }

    /**
     * Draw booster cards with realistic rarity weighting.
     *
     * Base structure: 3 commons + 1 uncommon + 1 rare slot
     * Multi-rare chance replaces common/uncommon slots with extra rare rolls:
     *   - 2 rares: 12%
     *   - 3 rares: 3%
     *   - 4 rares: 0.5%
     *   - 5 rares (god pack): 0.1%
     *
     * Each rare slot rolls independently:
     *   - 60% : Rare / Rare Holo
     *   - 25% : downgrade (uncommon)
     *   - 10% : Ultra Rare
     *   - 4%  : Illustration Rare
     *   - 1%  : Secret Rare
     */
    public function drawBoosterCards(array $cards, int $count = 5): array
    {
        $tiers = [
            'common'       => [],
            'uncommon'     => [],
            'rare'         => [],
            'ultra'        => [],
            'illustration' => [],
            'secret'       => [],
        ];

        foreach ($cards as $card) {
            $tier = $this->getRarityTier($card['rarity'] ?? 'Common');
            $tiers[$tier][] = $card;
        }

        $fallback = $cards;

        $pick = function (array $pool) use ($fallback) {
            $p = count($pool) > 0 ? $pool : $fallback;
            return $p[array_rand($p)];
        };

        $rollRare = function () use ($pick, $tiers) {
            $roll = mt_rand(1, 10000);

            if ($roll <= 30 && count($tiers['secret']) > 0) {
                return $pick($tiers['secret']);         // 0.3%
            } elseif ($roll <= 150 && count($tiers['illustration']) > 0) {
                return $pick($tiers['illustration']);    // 1.2%
            } elseif ($roll <= 600 && count($tiers['ultra']) > 0) {
                return $pick($tiers['ultra']);           // 4.5%
            } elseif (count($tiers['rare']) > 0) {
                return $pick($tiers['rare']);            // ~70%
            } else {
                return $pick($tiers['uncommon']);
            }
        };

        // Multi-rare roll (reduced chances)
        $multiRoll = mt_rand(1, 10000);
        if ($multiRoll <= 5) {
            $rareSlots = 5;   // 0.05% — God pack
        } elseif ($multiRoll <= 30) {
            $rareSlots = 4;   // 0.25%
        } elseif ($multiRoll <= 150) {
            $rareSlots = 3;   // 1.2%
        } elseif ($multiRoll <= 800) {
            $rareSlots = 2;   // 6.5%
        } else {
            $rareSlots = 1;   // 92%
        }

        $rareSlots   = min($rareSlots, $count);
        $normalSlots = $count - $rareSlots;

        $draw = [];

        for ($i = 0; $i < $normalSlots; $i++) {
            $draw[] = ($i < $normalSlots - 1)
                ? $pick($tiers['common'])
                : $pick($tiers['uncommon']);
        }

        for ($i = 0; $i < $rareSlots; $i++) {
            $draw[] = $rollRare();
        }

        return $draw;
    }
}
