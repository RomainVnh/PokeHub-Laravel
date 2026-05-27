<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PokemonTcgService
{
    private string $baseUrl = 'https://api.pokemontcg.io/v2';
    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.pokemon_tcg.key');
    }

    private function request(string $path): ?array
    {
        try {
            $request = Http::connectTimeout(15)->timeout(30)->retry(1, 500);

            if ($this->apiKey) {
                $request = $request->withHeaders(['X-Api-Key' => $this->apiKey]);
            }

            $response = $request->get("{$this->baseUrl}{$path}");

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all sets ordered by release date (newest first).
     */
    public function getAllSets(): array
    {
        $result = Cache::remember('ptcg_all_sets', 3600, function () {
            $data = $this->request('/sets?pageSize=250');
            if (!isset($data['data'])) {
                return null;
            }
            // Sort by release date descending in PHP (API orderBy is too slow)
            $sets = $data['data'];
            usort($sets, fn($a, $b) => strcmp($b['releaseDate'] ?? '', $a['releaseDate'] ?? ''));
            return $sets;
        });

        // Don't cache failures
        if (empty($result)) {
            Cache::forget('ptcg_all_sets');
            return [];
        }

        return $result;
    }

    /**
     * Get a single set by ID.
     */
    public function getSet(string $id): ?array
    {
        $result = Cache::remember("ptcg_set_{$id}", 3600, function () use ($id) {
            $data = $this->request("/sets/{$id}");
            return $data['data'] ?? null;
        });

        // Don't cache failures
        if ($result === null) {
            Cache::forget("ptcg_set_{$id}");
        }

        return $result;
    }

    /**
     * Get cards for a set.
     */
    public function getSetCards(string $setId, int $page = 1, int $pageSize = 250): array
    {
        $cacheKey = "ptcg_cards_{$setId}_{$page}_{$pageSize}";
        $result = Cache::remember($cacheKey, 3600, function () use ($setId, $page, $pageSize) {
            $data = $this->request("/cards?q=set.id:{$setId}&page={$page}&pageSize={$pageSize}");
            if (!isset($data['data'])) {
                return null;
            }
            // Sort by number in PHP (API orderBy is too slow)
            $cards = $data['data'];
            usort($cards, function($a, $b) {
                $numA = $a['number'] ?? '0';
                $numB = $b['number'] ?? '0';
                // Numeric sort when both are numbers, string sort otherwise
                if (is_numeric($numA) && is_numeric($numB)) {
                    return (int)$numA - (int)$numB;
                }
                return strcmp($numA, $numB);
            });
            return $cards;
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
        return Cache::remember("ptcg_card_{$id}", 3600, function () use ($id) {
            $data = $this->request("/cards/{$id}");
            return $data['data'] ?? null;
        });
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
        $byRarity = [];
        foreach ($cards as $card) {
            $rarity = $card['rarity'] ?? 'Common';
            $byRarity[$rarity][] = $card;
        }

        $commons   = $byRarity['Common']   ?? $cards;
        $uncommons = $byRarity['Uncommon'] ?? $cards;
        $fallback  = $cards;

        // Rarity pools
        $rareBasic = array_merge(
            $byRarity['Rare'] ?? [],
            $byRarity['Rare Holo'] ?? []
        );

        $ultraRare = array_merge(
            $byRarity['Rare Ultra'] ?? [],
            $byRarity['Rare Holo EX'] ?? [],
            $byRarity['Rare Holo GX'] ?? [],
            $byRarity['Rare Holo V'] ?? [],
            $byRarity['Rare Holo VMAX'] ?? [],
            $byRarity['Rare Holo VSTAR'] ?? [],
            $byRarity['Double Rare'] ?? [],
            $byRarity['Ultra Rare'] ?? [],
            $byRarity['Rare ACE'] ?? [],
            $byRarity['ACE SPEC Rare'] ?? [],
        );

        $illustrationRare = array_merge(
            $byRarity['Illustration Rare'] ?? [],
            $byRarity['Special Illustration Rare'] ?? [],
            $byRarity['Amazing Rare'] ?? [],
        );

        $secretRare = array_merge(
            $byRarity['Rare Secret'] ?? [],
            $byRarity['Rare Rainbow'] ?? [],
            $byRarity['Hyper Rare'] ?? [],
            $byRarity['Shiny Ultra Rare'] ?? [],
            $byRarity['Shiny Rare'] ?? [],
        );

        $pick = function(array $pool) use ($fallback) {
            $p = count($pool) > 0 ? $pool : $fallback;
            return $p[array_rand($p)];
        };

        // Roll a single rare slot
        $rollRare = function() use ($pick, $rareBasic, $ultraRare, $illustrationRare, $secretRare, $uncommons) {
            $roll = mt_rand(1, 1000);

            if ($roll <= 10 && count($secretRare) > 0) {
                return $pick($secretRare);       // 1%
            } elseif ($roll <= 50 && count($illustrationRare) > 0) {
                return $pick($illustrationRare);  // 4%
            } elseif ($roll <= 150 && count($ultraRare) > 0) {
                return $pick($ultraRare);         // 10%
            } elseif (count($rareBasic) > 0) {
                return $pick($rareBasic);          // 60% + 25% fallback
            } else {
                return $pick($uncommons);
            }
        };

        // Determine how many rare slots (multi-rare roll)
        $multiRoll = mt_rand(1, 1000);
        if ($multiRoll <= 1) {
            $rareSlots = 5;  // 0.1% — God pack: ALL rare
        } elseif ($multiRoll <= 6) {
            $rareSlots = 4;  // 0.5%
        } elseif ($multiRoll <= 36) {
            $rareSlots = 3;  // 3%
        } elseif ($multiRoll <= 156) {
            $rareSlots = 2;  // 12%
        } else {
            $rareSlots = 1;  // 84.4% — normal booster
        }

        $rareSlots = min($rareSlots, $count);

        $draw = [];
        $normalSlots = $count - $rareSlots;

        // Fill normal slots (commons + uncommon)
        for ($i = 0; $i < $normalSlots; $i++) {
            if ($i < $normalSlots - 1) {
                $draw[] = $pick($commons);
            } else {
                $draw[] = $pick($uncommons);
            }
        }

        // Fill rare slots
        for ($i = 0; $i < $rareSlots; $i++) {
            $draw[] = $rollRare();
        }

        return $draw;
    }
}
