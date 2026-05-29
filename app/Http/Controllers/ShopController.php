<?php

namespace App\Http\Controllers;

use App\Models\ShopItem;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Shop index — browse items by category.
     */
    public function index()
    {
        $user = Auth::user();

        $items = ShopItem::orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        // Get user's purchased item IDs
        $purchased = UserPurchase::where('user_id', $user->id)
            ->pluck('shop_item_id')
            ->toArray();

        // Include free items as "owned"
        $freeIds = ShopItem::where('price', 0)->pluck('id')->toArray();
        $owned = array_unique(array_merge($purchased, $freeIds));

        return view('shop', [
            'items'      => $items,
            'owned'      => $owned,
            'user'       => $user,
            'categories' => [
                'title'  => ['label' => 'Titres', 'icon' => 'crown', 'desc' => 'Affiche un titre prestigieux sur ton profil'],
                'frame'  => ['label' => 'Cadres', 'icon' => 'frame', 'desc' => 'Personnalise le cadre autour de ton avatar'],
                'sleeve' => ['label' => 'Sleeves', 'icon' => 'cards', 'desc' => 'Change le dos de tes cartes dans les boosters'],
            ],
        ]);
    }

    /**
     * Purchase an item.
     */
    public function buy(ShopItem $item)
    {
        $user = Auth::user();

        // Already purchased?
        $exists = UserPurchase::where('user_id', $user->id)
            ->where('shop_item_id', $item->id)
            ->exists();

        if ($exists || $item->price === 0) {
            return back()->with('error', 'Tu possèdes déjà cet article.');
        }

        if ($user->poketokens < $item->price) {
            return back()->with('error', 'Tu n\'as pas assez de PokéTokens pour cet achat.');
        }

        $user->decrement('poketokens', $item->price);

        UserPurchase::create([
            'user_id'      => $user->id,
            'shop_item_id' => $item->id,
        ]);

        return back()->with('success', "Tu as acheté « {$item->name} » pour {$item->price} PokéTokens !");
    }

    /**
     * Equip an owned item.
     */
    public function equip(ShopItem $item)
    {
        $user = Auth::user();

        // Check ownership (free items are always accessible)
        if ($item->price > 0) {
            $owns = UserPurchase::where('user_id', $user->id)
                ->where('shop_item_id', $item->id)
                ->exists();

            if (!$owns) {
                return back()->with('error', 'Tu ne possèdes pas cet article.');
            }
        }

        $field = match ($item->category) {
            'title'  => 'active_title',
            'frame'  => 'active_frame',
            'sleeve' => 'active_sleeve',
            default  => null,
        };

        if (!$field) {
            return back()->with('error', 'Catégorie inconnue.');
        }

        $user->update([$field => $item->slug]);

        return back()->with('success', "« {$item->name} » est maintenant équipé !");
    }

    /**
     * Unequip an item by category.
     */
    public function unequip(string $category)
    {
        $field = match ($category) {
            'title'  => 'active_title',
            'frame'  => 'active_frame',
            'sleeve' => 'active_sleeve',
            default  => null,
        };

        if (!$field) {
            return back()->with('error', 'Catégorie inconnue.');
        }

        Auth::user()->update([$field => null]);

        return back()->with('success', 'Article déséquipé.');
    }
}
