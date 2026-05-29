<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ──────────────────────────────────────────────
        // 1. Replace sleeve Mimiqui → Torterra
        // ──────────────────────────────────────────────
        DB::table('shop_items')->where('slug', 'sleeve-mimiki')->update([
            'slug'        => 'sleeve-torterra',
            'name'        => 'Torterra',
            'description' => 'Le continent vivant, gardien de la nature',
            'price'       => 1800,
            'data'        => json_encode(['image' => 'images/sleeves/torterra.jpg', 'color' => '#22c55e']),
        ]);

        // Reset users who had mimiki equipped
        DB::table('users')->where('active_sleeve', 'sleeve-mimiki')->update(['active_sleeve' => null]);

        // ──────────────────────────────────────────────
        // 2. Add Giratina sleeve (exclusive, 10 000 PT)
        // ──────────────────────────────────────────────
        DB::table('shop_items')->updateOrInsert(
            ['slug' => 'sleeve-giratina'],
            [
                'slug'        => 'sleeve-giratina',
                'name'        => 'Giratina',
                'description' => 'Le maitre du Monde Distorsion — Exclusif',
                'category'    => 'sleeve',
                'price'       => 10000,
                'sort_order'  => 53,
                'data'        => json_encode([
                    'image'     => 'images/sleeves/giratina.jpg',
                    'color'     => '#FFD700',
                    'exclusive' => true,
                ]),
            ]
        );

        // ──────────────────────────────────────────────
        // 3. Add Giratina avatar (exclusive, 8 000 PT)
        // ──────────────────────────────────────────────
        DB::table('shop_items')->updateOrInsert(
            ['slug' => 'avatar-giratina'],
            [
                'slug'        => 'avatar-giratina',
                'name'        => 'Giratina',
                'description' => 'L\'ombre du Monde Distorsion — Exclusif',
                'category'    => 'avatar',
                'price'       => 8000,
                'sort_order'  => 51,
                'data'        => json_encode([
                    'image'     => 'images/pfp/giratina.jpg',
                    'exclusive' => true,
                ]),
            ]
        );
    }

    public function down(): void
    {
        // Revert torterra back to mimiki
        DB::table('shop_items')->where('slug', 'sleeve-torterra')->update([
            'slug'        => 'sleeve-mimiki',
            'name'        => 'Mimiqui',
            'description' => 'Le spectre deguise en Pikachu',
            'data'        => json_encode(['image' => 'images/sleeves/mimiki.jpg', 'color' => '#FACC15']),
        ]);

        DB::table('shop_items')->where('slug', 'sleeve-giratina')->delete();
        DB::table('shop_items')->where('slug', 'avatar-giratina')->delete();
    }
};
