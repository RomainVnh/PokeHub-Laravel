<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ──────────────────────────────────────────────
        // 1. NEW SLEEVES (regular)
        // ──────────────────────────────────────────────
        $newSleeves = [
            [
                'slug'        => 'sleeve-ghetis',
                'name'        => 'Ghetis',
                'description' => 'Le leader de la Team Plasma',
                'category'    => 'sleeve',
                'price'       => 2000,
                'sort_order'  => 15,
                'data'        => json_encode(['image' => 'images/sleeves/ghetis.jpg', 'color' => '#6B21A8']),
            ],
            [
                'slug'        => 'sleeve-mewtwo-mew',
                'name'        => 'Mewtwo & Mew',
                'description' => 'Le duo psychique legendaire',
                'category'    => 'sleeve',
                'price'       => 3500,
                'sort_order'  => 16,
                'data'        => json_encode(['image' => 'images/sleeves/mewtwo_mew.jpg', 'color' => '#A855F7']),
            ],
            [
                'slug'        => 'sleeve-mimiki',
                'name'        => 'Mimiqui',
                'description' => 'Le spectre deguise en Pikachu',
                'category'    => 'sleeve',
                'price'       => 1800,
                'sort_order'  => 17,
                'data'        => json_encode(['image' => 'images/sleeves/mimiki.jpg', 'color' => '#FACC15']),
            ],
            [
                'slug'        => 'sleeve-osselet',
                'name'        => 'Osselait',
                'description' => 'Le petit guerrier solitaire',
                'category'    => 'sleeve',
                'price'       => 1500,
                'sort_order'  => 18,
                'data'        => json_encode(['image' => 'images/sleeves/osselet.jpg', 'color' => '#D4A843']),
            ],
            [
                'slug'        => 'sleeve-ronflex',
                'name'        => 'Ronflex',
                'description' => 'Le geant endormi',
                'category'    => 'sleeve',
                'price'       => 2500,
                'sort_order'  => 19,
                'data'        => json_encode(['image' => 'images/sleeves/ronflex.jpg', 'color' => '#1E3A5F']),
            ],
        ];

        foreach ($newSleeves as $sleeve) {
            DB::table('shop_items')->updateOrInsert(
                ['slug' => $sleeve['slug']],
                $sleeve
            );
        }

        // ──────────────────────────────────────────────
        // 2. EXCLUSIVE SLEEVES (gold tier, 10 000 PT)
        // ──────────────────────────────────────────────
        $exclusiveSleeves = [
            [
                'slug'        => 'sleeve-palkia',
                'name'        => 'Palkia',
                'description' => 'Maitre de l\'espace — Exclusif',
                'category'    => 'sleeve',
                'price'       => 10000,
                'sort_order'  => 50,
                'data'        => json_encode([
                    'image'     => 'images/sleeves/palkia.jpg',
                    'color'     => '#FFD700',
                    'exclusive' => true,
                ]),
            ],
            [
                'slug'        => 'sleeve-dialga',
                'name'        => 'Dialga',
                'description' => 'Maitre du temps — Exclusif',
                'category'    => 'sleeve',
                'price'       => 10000,
                'sort_order'  => 51,
                'data'        => json_encode([
                    'image'     => 'images/sleeves/dialga.jpg',
                    'color'     => '#FFD700',
                    'exclusive' => true,
                ]),
            ],
            [
                'slug'        => 'sleeve-arceus',
                'name'        => 'Arceus',
                'description' => 'Le Dieu Pokemon — Exclusif',
                'category'    => 'sleeve',
                'price'       => 10000,
                'sort_order'  => 52,
                'data'        => json_encode([
                    'image'     => 'images/sleeves/arceus.jpg',
                    'color'     => '#FFD700',
                    'exclusive' => true,
                ]),
            ],
        ];

        foreach ($exclusiveSleeves as $sleeve) {
            DB::table('shop_items')->updateOrInsert(
                ['slug' => $sleeve['slug']],
                $sleeve
            );
        }

        // ──────────────────────────────────────────────
        // 3. NEW AVATARS (regular)
        // ──────────────────────────────────────────────
        $newAvatars = [
            [
                'slug'        => 'avatar-arcanin',
                'name'        => 'Arcanin',
                'description' => 'Le Pokemon legendaire du feu',
                'category'    => 'avatar',
                'price'       => 1500,
                'sort_order'  => 10,
                'data'        => json_encode(['image' => 'images/pfp/arcanin.jpg']),
            ],
            [
                'slug'        => 'avatar-charbambin',
                'name'        => 'Charbambin',
                'description' => 'Le petit lezard de feu',
                'category'    => 'avatar',
                'price'       => 1000,
                'sort_order'  => 11,
                'data'        => json_encode(['image' => 'images/pfp/charbambin.jpg']),
            ],
            [
                'slug'        => 'avatar-draco',
                'name'        => 'Draco',
                'description' => 'Le puissant dragon',
                'category'    => 'avatar',
                'price'       => 2000,
                'sort_order'  => 12,
                'data'        => json_encode(['image' => 'images/pfp/draco.jpg']),
            ],
            [
                'slug'        => 'avatar-gardevoir-futur',
                'name'        => 'Gardevoir Futur',
                'description' => 'La gardienne du futur',
                'category'    => 'avatar',
                'price'       => 3000,
                'sort_order'  => 13,
                'data'        => json_encode(['image' => 'images/pfp/gardevoir_futur.jpg']),
            ],
            [
                'slug'        => 'avatar-lagron',
                'name'        => 'Galeking',
                'description' => 'Le titan d\'acier',
                'category'    => 'avatar',
                'price'       => 2500,
                'sort_order'  => 14,
                'data'        => json_encode(['image' => 'images/pfp/lagron.jpg']),
            ],
            [
                'slug'        => 'avatar-locklass',
                'name'        => 'Lokhlass',
                'description' => 'Le doux transporteur des mers',
                'category'    => 'avatar',
                'price'       => 1800,
                'sort_order'  => 15,
                'data'        => json_encode(['image' => 'images/pfp/locklass.jpg']),
            ],
            [
                'slug'        => 'avatar-poussacha',
                'name'        => 'Poussacha',
                'description' => 'Le starter herbe de Paldea',
                'category'    => 'avatar',
                'price'       => 1200,
                'sort_order'  => 16,
                'data'        => json_encode(['image' => 'images/pfp/poussacha.jpg']),
            ],
            [
                'slug'        => 'avatar-rayquaza',
                'name'        => 'Rayquaza',
                'description' => 'Le gardien du ciel',
                'category'    => 'avatar',
                'price'       => 3500,
                'sort_order'  => 17,
                'data'        => json_encode(['image' => 'images/pfp/rayquaza.jpg']),
            ],
            [
                'slug'        => 'avatar-tyranocif',
                'name'        => 'Tyranocif',
                'description' => 'Le destructeur des montagnes',
                'category'    => 'avatar',
                'price'       => 3000,
                'sort_order'  => 18,
                'data'        => json_encode(['image' => 'images/pfp/tyranocif.jpg']),
            ],
        ];

        foreach ($newAvatars as $avatar) {
            DB::table('shop_items')->updateOrInsert(
                ['slug' => $avatar['slug']],
                $avatar
            );
        }

        // ──────────────────────────────────────────────
        // 4. EXCLUSIVE AVATAR (deoxys, 8 000 PT)
        // ──────────────────────────────────────────────
        DB::table('shop_items')->updateOrInsert(
            ['slug' => 'avatar-deoxys'],
            [
                'slug'        => 'avatar-deoxys',
                'name'        => 'Deoxys',
                'description' => 'L\'entite extraterrestre — Exclusif',
                'category'    => 'avatar',
                'price'       => 8000,
                'sort_order'  => 50,
                'data'        => json_encode([
                    'image'     => 'images/pfp/deoxys.jpg',
                    'exclusive' => true,
                ]),
            ]
        );

        // ──────────────────────────────────────────────
        // 5. SET ALL USERS' POKETOKENS TO "INFINITY"
        // ──────────────────────────────────────────────
        DB::table('users')->update(['poketokens' => 9999999]);
    }

    public function down(): void
    {
        $slugs = [
            'sleeve-ghetis', 'sleeve-mewtwo-mew', 'sleeve-mimiki', 'sleeve-osselet', 'sleeve-ronflex',
            'sleeve-palkia', 'sleeve-dialga', 'sleeve-arceus',
            'avatar-arcanin', 'avatar-charbambin', 'avatar-draco', 'avatar-gardevoir-futur',
            'avatar-lagron', 'avatar-locklass', 'avatar-poussacha', 'avatar-rayquaza', 'avatar-tyranocif',
            'avatar-deoxys',
        ];

        DB::table('shop_items')->whereIn('slug', $slugs)->delete();
    }
};
