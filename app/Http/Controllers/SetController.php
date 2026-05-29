<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CollectionController;
use App\Models\UserCard;
use App\Models\UserFavorite;
use App\Services\PokemonTcgService;
use Illuminate\Support\Facades\Auth;

class SetController extends Controller
{
    public function __construct(
        private PokemonTcgService $tcg
    ) {}

    /**
     * Home page — featured sets.
     */
    public function home()
    {
        set_time_limit(180);
        $sets = $this->tcg->getAllSets();
        $featured = array_slice($sets, 0, 12);

        return view('home', [
            'sets'      => $sets,
            'featured'  => $featured,
            'totalSets' => count($sets),
        ]);
    }

    /**
     * Encyclopedia — all sets grouped by series.
     */
    public function encyclopedia()
    {
        set_time_limit(180);
        $sets = $this->tcg->getAllSets();

        $series = [];
        foreach ($sets as $set) {
            $series[$set['series']][] = $set;
        }

        // Collection progress per set for authenticated users
        $collectionProgress = [];
        if (Auth::check()) {
            $collectionProgress = UserCard::where('user_id', Auth::id())
                ->selectRaw('set_id, COUNT(DISTINCT card_id) as collected')
                ->groupBy('set_id')
                ->pluck('collected', 'set_id')
                ->toArray();
        }

        return view('encyclopedia', [
            'sets'               => $sets,
            'series'             => $series,
            'collectionProgress' => $collectionProgress,
        ]);
    }

    /**
     * Set detail — cards gallery (main page matching screenshot).
     */
    public function show(string $setId)
    {
        set_time_limit(180);

        $set = $this->tcg->getSet($setId);

        if (!$set) {
            return redirect()->route('encyclopedia')->with('error', 'Impossible de charger cette édition. L\'API est temporairement indisponible.');
        }

        $cards = $this->tcg->getSetCards($setId, 1, 250);

        // Strip card data to only what the view/popup needs (massive perf improvement)
        $stripCard = function (array $card): array {
            return [
                'id'        => $card['id'] ?? '',
                'name'      => $card['name'] ?? '',
                'supertype' => $card['supertype'] ?? '',
                'subtypes'  => $card['subtypes'] ?? [],
                'types'     => $card['types'] ?? [],
                'hp'        => $card['hp'] ?? null,
                'rarity'    => $card['rarity'] ?? null,
                'artist'    => $card['artist'] ?? null,
                'number'    => $card['number'] ?? '',
                'images'    => ['small' => $card['images']['small'] ?? ''],
            ];
        };

        $cards = array_map($stripCard, $cards);

        // Group cards by supertype
        $grouped = [];
        $counts  = ['Pokémon' => 0, 'Trainer' => 0, 'Energy' => 0];

        foreach ($cards as $card) {
            $type = $card['supertype'] ?? 'Other';
            $grouped[$type][] = $card;
            if (isset($counts[$type])) {
                $counts[$type]++;
            }
        }

        // Get collected cards and favorites for authenticated users
        $collectedCards = [];
        $favorites = [];
        if (Auth::check()) {
            $collectedCards = UserCard::where('user_id', Auth::id())
                ->where('set_id', $setId)
                ->pluck('quantity', 'card_id')
                ->toArray();

            $favorites = UserFavorite::where('user_id', Auth::id())
                ->pluck('card_id')
                ->toArray();
        }

        return view('set-detail', [
            'set'            => $set,
            'cards'          => $cards,
            'grouped'        => $grouped,
            'counts'         => $counts,
            'collectedCards' => $collectedCards,
            'favorites'      => $favorites,
        ]);
    }

    /**
     * Booster set selection.
     */
    public function openIndex()
    {
        set_time_limit(180);
        $sets = $this->tcg->getAllSets();

        return view('open-index', [
            'sets' => $sets,
        ]);
    }

    /**
     * Open a booster for a set.
     */
    public function openBooster(string $setId)
    {
        set_time_limit(180);
        $set = $this->tcg->getSet($setId);

        if (!$set) {
            return redirect()->route('open.index')->with('error', 'Impossible de charger cette édition. L\'API est temporairement indisponible.');
        }

        $cards = $this->tcg->getSetCards($setId, 1, 250);

        if (empty($cards)) {
            return redirect()->route('open.index')->with('error', 'Aucune carte trouvée pour cette édition.');
        }

        $drawn = $this->tcg->drawBoosterCards($cards, 5);

        $newCards = $this->trackDrawnCards($drawn, $setId);

        return view('open-booster', [
            'set'       => $set,
            'drawn'     => $drawn,
            'newCards'   => $newCards,
            'isPremium' => false,
        ]);
    }

    /**
     * Open a premium booster (guaranteed ultra+, higher rates).
     */
    public function openPremiumBooster(string $setId)
    {
        set_time_limit(180);
        $user = Auth::user();

        $canFreeOpen = !$user->last_premium_booster_at
            || !$user->last_premium_booster_at->isToday();

        if (!$canFreeOpen && $user->poketokens < 30000) {
            return redirect()->route('open.index')
                ->with('error', 'Tu as déjà utilisé ton booster premium gratuit aujourd\'hui et tu n\'as pas assez de PokéTokens (30 000 requis).');
        }

        $set = $this->tcg->getSet($setId);

        if (!$set) {
            return redirect()->route('open.index')->with('error', 'Impossible de charger cette édition.');
        }

        $cards = $this->tcg->getSetCards($setId, 1, 250);

        if (empty($cards)) {
            return redirect()->route('open.index')->with('error', 'Aucune carte trouvée pour cette édition.');
        }

        $drawn = $this->tcg->drawPremiumBoosterCards($cards, 5);

        if ($canFreeOpen) {
            $user->update(['last_premium_booster_at' => now()]);
        } else {
            $user->decrement('poketokens', 30000);
        }

        $newCards = $this->trackDrawnCards($drawn, $setId);

        return view('open-booster', [
            'set'       => $set,
            'drawn'     => $drawn,
            'newCards'   => $newCards,
            'isPremium' => true,
        ]);
    }

    private function trackDrawnCards(array $drawn, string $setId): array
    {
        $newCards = [];

        if (Auth::check()) {
            $userId = Auth::id();
            $drawnIds = array_map(fn($c) => $c['id'], $drawn);
            $existing = UserCard::where('user_id', $userId)
                ->whereIn('card_id', $drawnIds)
                ->pluck('card_id')
                ->toArray();

            foreach ($drawn as $card) {
                $cardId = $card['id'];
                $isNew = !in_array($cardId, $existing);
                $newCards[$cardId] = $isNew;

                if ($isNew) {
                    UserCard::create([
                        'user_id'   => $userId,
                        'card_id'   => $cardId,
                        'card_name' => $card['name'],
                        'set_id'    => $setId,
                        'rarity'    => $card['rarity'] ?? null,
                        'image_url' => $card['images']['small'] ?? '',
                    ]);
                } else {
                    UserCard::where('user_id', $userId)
                        ->where('card_id', $cardId)
                        ->increment('quantity');
                }
            }
        }

        return $newCards;
    }
}
