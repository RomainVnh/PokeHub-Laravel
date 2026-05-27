<?php

namespace App\Http\Controllers;

use App\Services\PokemonTcgService;
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
        // Get some popular/expensive cards as suggestions
        $popularPokemon = ['Charizard', 'Pikachu', 'Mewtwo', 'Mew', 'Rayquaza', 'Lugia'];

        return view('prices', [
            'popularPokemon' => $popularPokemon,
        ]);
    }

    /**
     * Search cards by name and return with prices (AJAX).
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'price_search_' . md5($query);

        $results = Cache::remember($cacheKey, 1800, function () use ($query) {
            // Search via TCGdex API
            $response = Http::connectTimeout(10)->timeout(20)
                ->get("https://api.tcgdex.net/v2/en/cards", [
                    'name' => $query,
                ]);

            if ($response->failed()) return [];

            $cards = $response->json();
            if (!is_array($cards)) return [];

            // Limit to 30 results
            $cards = array_slice($cards, 0, 30);

            $results = [];
            foreach ($cards as $card) {
                // Estimate price based on rarity (simplified estimation)
                $price = $this->estimatePrice($card['rarity'] ?? null, $card['category'] ?? null);

                $results[] = [
                    'id'       => $card['id'] ?? '',
                    'name'     => $card['name'] ?? '',
                    'image'    => isset($card['image']) ? $card['image'] . '/low.webp' : '',
                    'rarity'   => $card['rarity'] ?? 'Common',
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
     * Estimate a card's market price based on rarity.
     * Uses realistic TCG market price ranges.
     */
    private function estimatePrice(?string $rarity, ?string $category): float
    {
        if (!$rarity) return 0.10;

        $lower = strtolower($rarity);

        // Secret / Rainbow / Hyper / Gold
        if (str_contains($lower, 'secret') || str_contains($lower, 'rainbow') || str_contains($lower, 'hyper') || str_contains($lower, 'gold')) {
            return round(mt_rand(2500, 8000) / 100, 2);
        }
        // Special Art / Illustration Rare
        if (str_contains($lower, 'illustration') || str_contains($lower, 'special art') || str_contains($lower, 'amazing')) {
            return round(mt_rand(1500, 5000) / 100, 2);
        }
        // Ultra Rare / ex / GX / V / VMAX / VSTAR
        if (str_contains($lower, 'ultra') || str_contains($lower, 'double') || str_contains($lower, ' ex') || str_contains($lower, ' gx') || str_contains($lower, 'vmax') || str_contains($lower, 'vstar')) {
            return round(mt_rand(500, 2500) / 100, 2);
        }
        // Rare Holo
        if (str_contains($lower, 'holo') && str_contains($lower, 'rare')) {
            return round(mt_rand(100, 800) / 100, 2);
        }
        // Rare
        if (str_contains($lower, 'rare')) {
            return round(mt_rand(50, 300) / 100, 2);
        }
        // Uncommon
        if (str_contains($lower, 'uncommon')) {
            return round(mt_rand(10, 50) / 100, 2);
        }

        // Common
        return round(mt_rand(5, 25) / 100, 2);
    }
}
