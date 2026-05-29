<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCard;
use App\Models\UserFavorite;
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
                    'card_id'    => $card->card_id,
                    'card_name'  => $card->card_name,
                    'set_id'     => $card->set_id,
                    'rarity'     => $card->rarity,
                    'image_url'  => $card->image_url,
                    'quantity'   => $card->quantity,
                    'sellPrice'  => self::getSellPrice($card->rarity),
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

        // Favorites
        $favorites = UserFavorite::where('user_id', $userId)
            ->pluck('card_id')
            ->toArray();

        return view('collection', [
            'collection'  => $collection,
            'totalCards'   => $totalCards,
            'totalUnique'  => $totalUnique,
            'rarityStats'  => $rarityStats,
            'allSets'      => $allSets,
            'favorites'    => $favorites,
        ]);
    }

    public function sell(Request $request)
    {
        $request->validate([
            'card_id'  => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $cardId = $request->input('card_id');
        $qty    = $request->integer('quantity');

        $userCard = UserCard::where('user_id', $userId)
            ->where('card_id', $cardId)
            ->first();

        if (!$userCard || $userCard->quantity < $qty + 1) {
            return back()->with('error', 'Tu dois garder au moins 1 exemplaire de cette carte.');
        }

        $price = self::getSellPrice($userCard->rarity);
        $total = $price * $qty;

        $userCard->decrement('quantity', $qty);

        User::where('id', $userId)->increment('poketokens', $total);

        return back()->with('success', "Tu as vendu {$qty} carte(s) pour {$total} PokéTokens !");
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'card_id'   => 'required|string',
            'card_name' => 'required|string',
            'set_id'    => 'required|string',
            'image_url' => 'nullable|string',
            'rarity'    => 'nullable|string',
        ]);

        $userId = Auth::id();
        $cardId = $request->input('card_id');

        $existing = UserFavorite::where('user_id', $userId)
            ->where('card_id', $cardId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        UserFavorite::create([
            'user_id'   => $userId,
            'card_id'   => $cardId,
            'card_name' => $request->input('card_name'),
            'set_id'    => $request->input('set_id'),
            'image_url' => $request->input('image_url', ''),
            'rarity'    => $request->input('rarity'),
        ]);

        return response()->json(['favorited' => true]);
    }

    public static function getSellPrice(?string $rarity): int
    {
        if (!$rarity) return 10;

        $lower = strtolower($rarity);

        if (str_contains($lower, 'secret') || str_contains($lower, 'rainbow')
            || str_contains($lower, 'hyper') || str_contains($lower, 'gold')
            || (str_contains($lower, 'shiny') && str_contains($lower, 'ultra'))) {
            return 1500;
        }
        if (str_contains($lower, 'illustration') || str_contains($lower, 'amazing')) {
            return 800;
        }
        if (str_contains($lower, 'ultra') || str_contains($lower, 'double')
            || str_contains($lower, 'ace') || str_contains($lower, ' ex')
            || str_contains($lower, ' gx') || str_contains($lower, 'vmax')
            || str_contains($lower, 'vstar')) {
            return 400;
        }
        if (str_contains($lower, 'rare') || str_contains($lower, 'holo')) {
            return 100;
        }
        if (str_contains($lower, 'uncommon')) {
            return 30;
        }

        return 10;
    }
}
