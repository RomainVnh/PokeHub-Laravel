<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('level')->default(1)->after('poketokens');
            $table->unsignedInteger('xp')->default(0)->after('level');
        });

        // ── Level 10 rewards ───────────────────────────────────────
        // Avatar: Dresseur Hisui (epic, purple/white border)
        DB::table('shop_items')->updateOrInsert(
            ['slug' => 'avatar-dresseur-hisui'],
            [
                'slug'        => 'avatar-dresseur-hisui',
                'name'        => 'Dresseur de Hisui',
                'description' => 'Recompense niveau 10 — Epique',
                'category'    => 'avatar',
                'price'       => 0,
                'sort_order'  => 99,
                'data'        => json_encode([
                    'image'        => 'images/pfp/dresseur_hisui.jpg',
                    'exclusive'    => true,
                    'level_reward' => 10,
                ]),
            ]
        );

        // Title: Chasseur (level 10 reward)
        DB::table('shop_items')->updateOrInsert(
            ['slug' => 'chasseur'],
            [
                'slug'        => 'chasseur',
                'name'        => 'Chasseur',
                'description' => 'Recompense niveau 10',
                'category'    => 'title',
                'price'       => 0,
                'sort_order'  => 99,
                'data'        => json_encode([
                    'gradient'     => 'linear-gradient(135deg, #a855f7, #ffffff, #c084fc)',
                    'shadow'       => 'drop-shadow(0 0 6px rgba(168,85,247,0.5)) drop-shadow(0 0 12px rgba(168,85,247,0.3))',
                    'animated'     => true,
                    'level_reward' => 10,
                ]),
            ]
        );
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['level', 'xp']);
        });

        DB::table('shop_items')->whereIn('slug', ['avatar-dresseur-hisui', 'chasseur'])->delete();
    }
};
