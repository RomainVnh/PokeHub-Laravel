<?php

namespace App\Http\Controllers;

use App\Services\PokemonTcgService;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PriceController extends Controller
{
    public function __construct(
        private PokemonTcgService $tcg
    ) {}

    /**
     * Price estimation page — search cards and display estimated prices.
     */
    public function index()
    {
        set_time_limit(180);

        $popularPokemon = ['Charizard', 'Pikachu', 'Mewtwo', 'Mew', 'Rayquaza', 'Lugia'];

        // Fetch featured cards (cached for 6 hours)
        $featuredCards = Cache::remember('price_featured_cards', 21600, function () {
            $featured = [];
            $queries = ['Charizard', 'Pikachu', 'Mewtwo', 'Rayquaza', 'Lugia', 'Gardevoir', 'Arceus', 'Gengar'];

            foreach ($queries as $q) {
                $response = Http::connectTimeout(10)->timeout(20)
                    ->get("https://api.tcgdex.net/v2/en/cards", ['name' => $q]);

                if ($response->failed()) continue;

                $cards = $response->json();
                if (!is_array($cards) || empty($cards)) continue;

                // Pick 3 random cards per Pokemon for variety
                shuffle($cards);
                $picks = array_slice($cards, 0, 3);

                // Fetch full details concurrently
                $responses = Http::pool(fn ($pool) =>
                    array_map(
                        fn ($c) => $pool->connectTimeout(10)->timeout(15)
                            ->get("https://api.tcgdex.net/v2/en/cards/{$c['id']}"),
                        $picks
                    )
                );

                foreach ($responses as $resp) {
                    if ($resp instanceof \Illuminate\Http\Client\Response && $resp->successful()) {
                        $card = $resp->json();
                        if (!$card || empty($card['image'] ?? '')) continue;

                        $cardId   = $card['id'] ?? '';
                        $rarity   = $card['rarity'] ?? null;
                        $category = $card['category'] ?? null;
                        $price    = $this->estimatePrice($cardId, $rarity, $category);

                        $featured[] = [
                            'id'     => $cardId,
                            'name'   => $card['name'] ?? '',
                            'image'  => ($card['image'] ?? '') . '/low.webp',
                            'rarity' => $rarity ?? 'Common',
                            'set'    => $card['set']['name'] ?? '',
                            'setId'  => $card['set']['id'] ?? '',
                            'price'  => $price,
                        ];
                    }
                }
            }

            // Sort by price descending and keep top 18
            usort($featured, fn($a, $b) => $b['price'] <=> $a['price']);
            return array_slice($featured, 0, 18);
        });

        return view('prices', [
            'popularPokemon' => $popularPokemon,
            'featuredCards'   => $featuredCards,
        ]);
    }

    /**
     * Search cards by name and return with prices (AJAX).
     *
     * Fetches card list, then fetches each card's detail concurrently
     * to get rarity, set name, category — data the list endpoint omits.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'price_search_' . md5($query);

        $results = Cache::remember($cacheKey, 1800, function () use ($query) {
            // Search via TCGdex API (list endpoint — minimal data)
            $response = Http::connectTimeout(10)->timeout(20)
                ->get("https://api.tcgdex.net/v2/en/cards", [
                    'name' => $query,
                ]);

            if ($response->failed()) return [];

            $cards = $response->json();
            if (!is_array($cards)) return [];

            // Limit to 24 results, then fetch full details concurrently
            $cards = array_slice($cards, 0, 24);

            // Fetch each card's full detail (for rarity, set, category)
            $detailedCards = [];
            $chunks = array_chunk($cards, 12);

            foreach ($chunks as $chunk) {
                $responses = Http::pool(fn (Pool $pool) =>
                    array_map(
                        fn ($c) => $pool->connectTimeout(10)->timeout(20)
                            ->get("https://api.tcgdex.net/v2/en/cards/{$c['id']}"),
                        $chunk
                    )
                );

                foreach ($responses as $response) {
                    if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                        $card = $response->json();
                        if ($card) {
                            $detailedCards[] = $card;
                        }
                    }
                }
            }

            $results = [];
            foreach ($detailedCards as $card) {
                $cardId = $card['id'] ?? '';
                $rarity = $card['rarity'] ?? null;
                $category = $card['category'] ?? null;
                $name = $card['name'] ?? '';

                // Deterministic price based on card ID + rarity
                $price = $this->estimatePrice($cardId, $rarity, $category);

                $results[] = [
                    'id'       => $cardId,
                    'name'     => $name,
                    'image'    => isset($card['image']) ? $card['image'] . '/low.webp' : '',
                    'rarity'   => $rarity ?? 'Common',
                    'set'      => $card['set']['name'] ?? '',
                    'setId'    => $card['set']['id'] ?? '',
                    'localId'  => $card['localId'] ?? '',
                    'price'    => $price,
                ];
            }

            // Sort by estimated price descending
            usort($results, fn($a, $b) => $b['price'] <=> $a['price']);

            return $results;
        });

        return response()->json($results);
    }

    /**
     * Estimate a card's market price based on rarity + card ID.
     *
     * Uses a deterministic hash of the card ID to generate a consistent
     * price within the rarity's price range — same card always shows
     * the same price, but different cards show different prices.
     */
    private function estimatePrice(string $cardId, ?string $rarity, ?string $category): float
    {
        // Deterministic seed from card ID (0.0 to 1.0)
        $hash = crc32($cardId);
        $seed = abs($hash) / 4294967295;

        if (!$rarity) {
            return round(0.05 + $seed * 0.20, 2);
        }

        $lower = strtolower($rarity);

        // Secret / Rainbow / Hyper / Gold
        if (str_contains($lower, 'secret') || str_contains($lower, 'rainbow') || str_contains($lower, 'hyper') || str_contains($lower, 'gold')) {
            return round(25.00 + $seed * 55.00, 2);
        }
        // Special Art / Illustration Rare
        if (str_contains($lower, 'illustration') || str_contains($lower, 'special art') || str_contains($lower, 'amazing')) {
            return round(15.00 + $seed * 35.00, 2);
        }
        // Ultra Rare / ex / GX / V / VMAX / VSTAR
        if (str_contains($lower, 'ultra') || str_contains($lower, 'double') || str_contains($lower, ' ex') || str_contains($lower, ' gx') || str_contains($lower, 'vmax') || str_contains($lower, 'vstar')) {
            return round(5.00 + $seed * 20.00, 2);
        }
        // Rare Holo
        if (str_contains($lower, 'holo') && str_contains($lower, 'rare')) {
            return round(1.00 + $seed * 7.00, 2);
        }
        // Rare
        if (str_contains($lower, 'rare')) {
            return round(0.50 + $seed * 2.50, 2);
        }
        // Uncommon
        if (str_contains($lower, 'uncommon')) {
            return round(0.10 + $seed * 0.40, 2);
        }

        // Common
        return round(0.05 + $seed * 0.20, 2);
    }
}
