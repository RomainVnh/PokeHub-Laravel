<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Delete old gradient-only sleeves that were replaced by image-based ones ──
        $oldSleeveSlugs = [
            'sleeve-pokeball', 'sleeve-pikachu', 'sleeve-evoli', 'sleeve-ultraball',
            'sleeve-leviator', 'sleeve-dracaufeu', 'sleeve-mewtwo', 'sleeve-masterball', 'sleeve-rayquaza',
        ];
        DB::table('shop_items')->whereIn('slug', $oldSleeveSlugs)->delete();

        // Also reset users that had these old sleeves equipped
        DB::table('users')->whereIn('active_sleeve', $oldSleeveSlugs)->update(['active_sleeve' => null]);

        // ── 2. Insert new image-based sleeves ──────────────────────────
        $newSleeves = [
            ['slug' => 'sleeve-flagada',            'category' => 'sleeve', 'name' => 'Sleeve Ramoloss',       'description' => 'Ramoloss flotte paisiblement dans une eau cristalline.',         'price' => 1000, 'sort_order' => 2,  'data' => json_encode(['image' => 'images/sleeves/flagada.jpg', 'color' => '#f472b6'])],
            ['slug' => 'sleeve-noctali',            'category' => 'sleeve', 'name' => 'Sleeve Noctali',        'description' => "Noctali se repose à l'ombre d'un après-midi paisible.",           'price' => 1500, 'sort_order' => 3,  'data' => json_encode(['image' => 'images/sleeves/noctali.jpg', 'color' => '#fbbf24'])],
            ['slug' => 'sleeve-altaria',            'category' => 'sleeve', 'name' => 'Sleeve Altaria',        'description' => 'Altaria plane dans un ciel de nuages dorés au crépuscule.',       'price' => 1500, 'sort_order' => 4,  'data' => json_encode(['image' => 'images/sleeves/altaria.jpg', 'color' => '#93c5fd'])],
            ['slug' => 'sleeve-dragonfly',          'category' => 'sleeve', 'name' => 'Sleeve Libégon',        'description' => "Libégon contemple l'horizon sous un ciel immense.",               'price' => 2000, 'sort_order' => 5,  'data' => json_encode(['image' => 'images/sleeves/dragonfly.jpg', 'color' => '#86efac'])],
            ['slug' => 'sleeve-lucario',            'category' => 'sleeve', 'name' => 'Sleeve Lucario',        'description' => "Lucario médite dans le silence d'une forêt enneigée.",            'price' => 2500, 'sort_order' => 6,  'data' => json_encode(['image' => 'images/sleeves/lucario.jpg', 'color' => '#93c5fd'])],
            ['slug' => 'sleeve-dracolosse',         'category' => 'sleeve', 'name' => 'Sleeve Dracolosse',     'description' => "Dracolosse s'envole devant la pleine lune.",                      'price' => 3000, 'sort_order' => 7,  'data' => json_encode(['image' => 'images/sleeves/dracolosse.jpg', 'color' => '#fdba74'])],
            ['slug' => 'sleeve-lugia',              'category' => 'sleeve', 'name' => 'Sleeve Lugia',          'description' => 'Lugia survole les îles dans un ciel sans limites.',                'price' => 3500, 'sort_order' => 8,  'data' => json_encode(['image' => 'images/sleeves/lugia.jpg', 'color' => '#e0e7ff'])],
            ['slug' => 'sleeve-dracolosseetraquaza','category' => 'sleeve', 'name' => 'Sleeve Duo Légendaire', 'description' => 'Dracolosse et Rayquaza, maîtres du ciel, unis dans les nuages.',  'price' => 4000, 'sort_order' => 9,  'data' => json_encode(['image' => 'images/sleeves/dracolosseetraquaza.jpg', 'color' => '#c4b5fd'])],
            ['slug' => 'sleeve-vague',              'category' => 'sleeve', 'name' => 'Sleeve Grande Vague',   'description' => 'Pikachu et ses amis surfent sur la Grande Vague.',                'price' => 5000, 'sort_order' => 10, 'data' => json_encode(['image' => 'images/sleeves/vague.jpg', 'color' => '#60a5fa'])],
        ];

        $now = now();
        foreach ($newSleeves as $sleeve) {
            DB::table('shop_items')->updateOrInsert(
                ['slug' => $sleeve['slug']],
                array_merge($sleeve, ['preview' => '', 'created_at' => $now, 'updated_at' => $now]),
            );
        }

        // ── 3. Update title effects with animated drop-shadows ─────────
        $titleUpdates = [
            'dresseur-novice'      => ['gradient' => 'linear-gradient(135deg, #9CA3AF, #6B7280)', 'shadow' => 'drop-shadow(0 1px 3px rgba(156,163,175,0.3))'],
            'meilleur-dresseur'    => ['gradient' => 'linear-gradient(90deg, #bf953f, #fcf6ba, #d4a843, #fcf6ba, #bf953f)', 'shadow' => 'drop-shadow(0 0 6px rgba(255,215,0,0.6)) drop-shadow(0 2px 4px rgba(191,149,63,0.4))'],
            'champion-arene'       => ['gradient' => 'linear-gradient(90deg, #ff4500, #ff6347, #ffd700, #ff6347, #ff4500)', 'shadow' => 'drop-shadow(0 0 8px rgba(255,69,0,0.6)) drop-shadow(0 0 16px rgba(255,165,0,0.3))'],
            'rival-eternel'        => ['gradient' => 'linear-gradient(90deg, #3b82f6, #6366f1, #a855f7, #ec4899, #ef4444)', 'shadow' => 'drop-shadow(-2px 0 6px rgba(59,130,246,0.5)) drop-shadow(2px 0 6px rgba(239,68,68,0.5))'],
            'chasseur-shinies'     => ['gradient' => 'linear-gradient(90deg, #ffd700, #fffacd, #ffd700, #fff8dc, #ffd700, #fffacd)', 'shadow' => 'drop-shadow(0 0 4px rgba(255,250,205,0.8)) drop-shadow(0 0 12px rgba(255,215,0,0.5)) drop-shadow(0 0 20px rgba(255,215,0,0.2))'],
            'collectionneur-elite' => ['gradient' => 'linear-gradient(135deg, #059669, #34d399, #a7f3d0, #34d399, #059669)', 'shadow' => 'drop-shadow(0 0 8px rgba(52,211,153,0.5)) drop-shadow(0 2px 6px rgba(5,150,105,0.4))'],
            'gardien-aura'         => ['gradient' => 'linear-gradient(90deg, #06b6d4, #8b5cf6, #ec4899, #8b5cf6, #06b6d4)', 'shadow' => 'drop-shadow(0 0 6px rgba(6,182,212,0.5)) drop-shadow(0 0 6px rgba(139,92,246,0.5)) drop-shadow(0 0 6px rgba(236,72,153,0.5))', 'animated' => true],
            'maitre-ligue'         => ['gradient' => 'linear-gradient(90deg, #ffd700, #a382ff, #ffd700, #a382ff, #ffd700)', 'shadow' => 'drop-shadow(0 0 8px rgba(163,130,255,0.6)) drop-shadow(0 0 16px rgba(255,215,0,0.4)) drop-shadow(0 2px 4px rgba(0,0,0,0.3))', 'animated' => true],
            'roi-des-dragons'      => ['gradient' => 'linear-gradient(135deg, #1e3a8a, #7c3aed, #06b6d4, #10b981, #06b6d4, #7c3aed)', 'shadow' => 'drop-shadow(0 0 10px rgba(124,58,237,0.6)) drop-shadow(0 0 20px rgba(6,182,212,0.3)) drop-shadow(0 3px 6px rgba(0,0,0,0.4))', 'animated' => true],
            'legende-vivante'      => ['gradient' => 'linear-gradient(90deg, #ff0000, #ff8c00, #ffd700, #00ff88, #00bfff, #8b5cf6, #ff0080, #ff0000)', 'shadow' => 'drop-shadow(0 0 6px rgba(255,0,0,0.35)) drop-shadow(0 0 6px rgba(0,191,255,0.35)) drop-shadow(0 0 6px rgba(139,92,246,0.35)) drop-shadow(0 0 14px rgba(255,215,0,0.4))', 'animated' => true],
        ];

        foreach ($titleUpdates as $slug => $data) {
            DB::table('shop_items')->where('slug', $slug)->update([
                'data' => json_encode($data),
                'updated_at' => $now,
            ]);
        }

        // ── 4. Insert avatar shop items ────────────────────────────────
        $avatars = [
            ['slug' => 'avatar-bulbizarre',      'category' => 'avatar', 'name' => 'Bulbizarre',         'description' => 'Le starter plante adoré de la première génération.',      'price' => 0,    'sort_order' => 1, 'data' => json_encode(['image' => 'images/pfp/bulbizarre.jpg'])],
            ['slug' => 'avatar-amphinobi',        'category' => 'avatar', 'name' => 'Amphinobi',          'description' => 'Le ninja aquatique, rapide et redoutable.',               'price' => 1500, 'sort_order' => 2, 'data' => json_encode(['image' => 'images/pfp/amphinobi.jpg'])],
            ['slug' => 'avatar-lucario',          'category' => 'avatar', 'name' => 'Lucario',            'description' => 'Le gardien de l\'aura, noble et puissant.',               'price' => 2000, 'sort_order' => 3, 'data' => json_encode(['image' => 'images/pfp/lucario.jpg'])],
            ['slug' => 'avatar-dracolosse',       'category' => 'avatar', 'name' => 'Dracolosse',         'description' => 'Le dragon bienveillant au regard déterminé.',              'price' => 2500, 'sort_order' => 4, 'data' => json_encode(['image' => 'images/pfp/dracolosse.jpg'])],
            ['slug' => 'avatar-dracaufeu-shiny',  'category' => 'avatar', 'name' => 'Dracaufeu Shiny',    'description' => 'La version chromatique légendaire de Dracaufeu.',          'price' => 4000, 'sort_order' => 5, 'data' => json_encode(['image' => 'images/pfp/dracaufeu_shiny.jpg'])],
            ['slug' => 'avatar-mega-dracaufeu-x', 'category' => 'avatar', 'name' => 'Méga-Dracaufeu X',   'description' => 'La méga-évolution aux flammes bleues dévastatrices.',     'price' => 5000, 'sort_order' => 6, 'data' => json_encode(['image' => 'images/pfp/mega_dracaufeu_x.jpg'])],
        ];

        foreach ($avatars as $avatar) {
            DB::table('shop_items')->updateOrInsert(
                ['slug' => $avatar['slug']],
                array_merge($avatar, ['preview' => '', 'created_at' => $now, 'updated_at' => $now]),
            );
        }
    }

    public function down(): void
    {
        DB::table('shop_items')->where('category', 'avatar')->delete();
        $imageSlugs = [
            'sleeve-flagada','sleeve-noctali','sleeve-altaria','sleeve-dragonfly',
            'sleeve-lucario','sleeve-dracolosse','sleeve-lugia','sleeve-dracolosseetraquaza','sleeve-vague',
        ];
        DB::table('shop_items')->whereIn('slug', $imageSlugs)->delete();
    }
};
