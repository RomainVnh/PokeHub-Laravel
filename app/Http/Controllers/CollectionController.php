<?php

namespace App\Http\Controllers;

use App\Models\UserCard;
use App\Services\PokemonTcgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function __construct(
        private PokemonTcgService $tcg
    ) {}

    /**
     * My collection — all cards the user owns, with advanced filters.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all user cards grouped by set
        $userCards = UserCard::where('user_id', $userId)
            ->get()
            ->groupBy('set_id');

        // Get all sets for filter options
        $allSets = $this->tcg->getAllSets();
        $setsById = [];
        foreach ($allSets as $set) {
            $setsById[$set['id']] = $set;
        }

        // Build collection data
        $collection = [];
        $totalCards = 0;
        $totalUnique = 0;
        $rarityStats = [];

        foreach ($userCards as $setId => $cards) {
            $setInfo = $setsById[$setId] ?? null;
            if (!$setInfo) continue;

            $setCards = [];
            foreach ($cards as $card) {
                $setCards[] = [
                    'card_id'   => $card->card_id,
                    'card_name' => $card->card_name,
                    'set_id'    => $card->set_id,
                    'rarity'    => $card->rarity,
                    'image_url' => $card->image_url,
                    'quantity'  => $card->quantity,
                ];
                $totalCards += $card->quantity;
                $totalUnique++;

                $r = $card->rarity ?? 'Common';
                $rarityStats[$r] = ($rarityStats[$r] ?? 0) + 1;
            }

            $collection[] = [
                'set'   => $setInfo,
                'cards' => $setCards,
                'count' => count($setCards),
            ];
        }

        // Sort sets by release date (newest first)
        usort($collection, fn($a, $b) => strcmp($b['set']['releaseDate'], $a['set']['releaseDate']));

        // Sort rarity stats by count
        arsort($rarityStats);

        return view('collection', [
            'collection'  => $collection,
            'totalCards'   => $totalCards,
            'totalUnique'  => $totalUnique,
            'rarityStats'  => $rarityStats,
            'allSets'      => $allSets,
        ]);
    }
}
