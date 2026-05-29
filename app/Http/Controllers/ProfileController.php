<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ShopItem;
use App\Models\UserCard;
use App\Models\UserPurchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Get user's purchased item IDs
        $purchasedIds = UserPurchase::where('user_id', $user->id)
            ->pluck('shop_item_id')
            ->toArray();

        // Free items are always owned
        $freeIds = ShopItem::where('price', 0)->pluck('id')->toArray();
        $ownedIds = array_unique(array_merge($purchasedIds, $freeIds));

        // Get owned items by category
        $ownedItems = ShopItem::whereIn('id', $ownedIds)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        // Active cosmetics
        $activeTitle  = $user->active_title ? ShopItem::where('slug', $user->active_title)->first() : null;
        $activeFrame  = $user->active_frame ? ShopItem::where('slug', $user->active_frame)->first() : null;
        $activeSleeve = $user->active_sleeve ? ShopItem::where('slug', $user->active_sleeve)->first() : null;

        // Card collection count
        $cardsCount = UserCard::where('user_id', $user->id)->count();

        return view('profile.edit', [
            'user'         => $user,
            'ownedItems'   => $ownedItems,
            'activeTitle'  => $activeTitle,
            'activeFrame'  => $activeFrame,
            'activeSleeve' => $activeSleeve,
            'cardsCount'   => $cardsCount,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update(['avatar' => $request->avatar]);

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
