<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $items = [
            // ═══ TITLES ═══════════════════════════════════════════════
            ['slug' => 'dresseur-novice',     'category' => 'title', 'name' => 'Dresseur Novice',          'description' => 'Le titre de base pour tout dresseur débutant.',                'price' => 0,     'sort_order' => 1,  'data' => json_encode([
                'gradient' => 'linear-gradient(135deg, #9CA3AF, #6B7280)',
                'shadow'   => 'drop-shadow(0 1px 3px rgba(156,163,175,0.3))',
            ])],
            ['slug' => 'meilleur-dresseur',   'category' => 'title', 'name' => 'Meilleur Dresseur',        'description' => 'Prouve ta valeur avec ce titre doré prestigieux.',              'price' => 2000,  'sort_order' => 2,  'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #bf953f, #fcf6ba, #d4a843, #fcf6ba, #bf953f)',
                'shadow'   => 'drop-shadow(0 0 6px rgba(255,215,0,0.6)) drop-shadow(0 2px 4px rgba(191,149,63,0.4))',
            ])],
            ['slug' => 'champion-arene',      'category' => 'title', 'name' => "Champion d'Arène",         'description' => "Un titre de braise pour les combattants d'élite.",              'price' => 3000,  'sort_order' => 3,  'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #ff4500, #ff6347, #ffd700, #ff6347, #ff4500)',
                'shadow'   => 'drop-shadow(0 0 8px rgba(255,69,0,0.6)) drop-shadow(0 0 16px rgba(255,165,0,0.3))',
            ])],
            ['slug' => 'rival-eternel',       'category' => 'title', 'name' => 'Rival Éternel',            'description' => 'Le feu et la glace s\'affrontent dans ce titre bicolore.',      'price' => 2500,  'sort_order' => 4,  'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #3b82f6, #6366f1, #a855f7, #ec4899, #ef4444)',
                'shadow'   => 'drop-shadow(-2px 0 6px rgba(59,130,246,0.5)) drop-shadow(2px 0 6px rgba(239,68,68,0.5))',
            ])],
            ['slug' => 'chasseur-shinies',    'category' => 'title', 'name' => 'Chasseur de Shinies',      'description' => 'Un éclat scintillant pour les traqueurs de raretés.',            'price' => 3500,  'sort_order' => 5,  'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #ffd700, #fffacd, #ffd700, #fff8dc, #ffd700, #fffacd)',
                'shadow'   => 'drop-shadow(0 0 4px rgba(255,250,205,0.8)) drop-shadow(0 0 12px rgba(255,215,0,0.5)) drop-shadow(0 0 20px rgba(255,215,0,0.2))',
            ])],
            ['slug' => 'collectionneur-elite','category' => 'title', 'name' => "Collectionneur d'Élite",   'description' => 'Un dégradé émeraude pour les collectionneurs dévoués.',          'price' => 4000,  'sort_order' => 6,  'data' => json_encode([
                'gradient' => 'linear-gradient(135deg, #059669, #34d399, #a7f3d0, #34d399, #059669)',
                'shadow'   => 'drop-shadow(0 0 8px rgba(52,211,153,0.5)) drop-shadow(0 2px 6px rgba(5,150,105,0.4))',
            ])],
            ['slug' => 'gardien-aura',        'category' => 'title', 'name' => "Gardien de l'Aura",        'description' => "Triple halo néon : cyan, violet et rose fusionnent.",            'price' => 4500,  'sort_order' => 7,  'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #06b6d4, #8b5cf6, #ec4899, #8b5cf6, #06b6d4)',
                'shadow'   => 'drop-shadow(0 0 6px rgba(6,182,212,0.5)) drop-shadow(0 0 6px rgba(139,92,246,0.5)) drop-shadow(0 0 6px rgba(236,72,153,0.5))',
            ])],
            ['slug' => 'maitre-ligue',        'category' => 'title', 'name' => 'Maître de la Ligue',       'description' => 'Aura royale — or et pourpre entrelacés.',                       'price' => 5000,  'sort_order' => 8,  'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #ffd700, #a382ff, #ffd700, #a382ff, #ffd700)',
                'shadow'   => 'drop-shadow(0 0 8px rgba(163,130,255,0.6)) drop-shadow(0 0 16px rgba(255,215,0,0.4)) drop-shadow(0 2px 4px rgba(0,0,0,0.3))',
            ])],
            ['slug' => 'roi-des-dragons',     'category' => 'title', 'name' => 'Roi des Dragons',          'description' => 'La puissance draconique irradie de chaque lettre.',              'price' => 6000,  'sort_order' => 9,  'data' => json_encode([
                'gradient' => 'linear-gradient(135deg, #1e3a8a, #7c3aed, #06b6d4, #10b981, #06b6d4, #7c3aed)',
                'shadow'   => 'drop-shadow(0 0 10px rgba(124,58,237,0.6)) drop-shadow(0 0 20px rgba(6,182,212,0.3)) drop-shadow(0 3px 6px rgba(0,0,0,0.4))',
            ])],
            ['slug' => 'legende-vivante',     'category' => 'title', 'name' => 'Légende Vivante',          'description' => 'Prismatique — toutes les couleurs de l\'arc-en-ciel.',           'price' => 10000, 'sort_order' => 10, 'data' => json_encode([
                'gradient' => 'linear-gradient(90deg, #ff0000, #ff8c00, #ffd700, #00ff88, #00bfff, #8b5cf6, #ff0080, #ff0000)',
                'shadow'   => 'drop-shadow(0 0 6px rgba(255,0,0,0.35)) drop-shadow(0 0 6px rgba(0,191,255,0.35)) drop-shadow(0 0 6px rgba(139,92,246,0.35)) drop-shadow(0 0 14px rgba(255,215,0,0.4))',
            ])],

            // ═══ FRAMES ═══════════════════════════════════════════════
            ['slug' => 'cadre-classique',   'category' => 'frame', 'name' => 'Cadre Classique',     'description' => 'Le cadre par défaut, simple et sobre.',                           'price' => 0,    'sort_order' => 1,  'data' => json_encode(['border' => '2px solid var(--border)', 'shadow' => 'none'])],
            ['slug' => 'cadre-dore',        'category' => 'frame', 'name' => 'Cadre Doré',          'description' => "Un cadre doré élégant autour de ton avatar.",                      'price' => 1500, 'sort_order' => 2,  'data' => json_encode(['border' => '2px solid #ffd700', 'shadow' => '0 0 12px rgba(255,215,0,0.4), inset 0 0 6px rgba(255,215,0,0.1)'])],
            ['slug' => 'cadre-nature',      'category' => 'frame', 'name' => 'Cadre Sylvestre',     'description' => 'Un cadre verdoyant inspiré de la forêt de Jade.',                 'price' => 1800, 'sort_order' => 3,  'data' => json_encode(['border' => '2px solid #22c55e', 'shadow' => '0 0 10px rgba(34,197,94,0.45), 0 0 18px rgba(16,185,129,0.2)'])],
            ['slug' => 'cadre-feu',         'category' => 'frame', 'name' => 'Cadre Flamme',        'description' => "Un cadre ardent qui brûle d'intensité autour de ton avatar.",      'price' => 2000, 'sort_order' => 4,  'data' => json_encode(['border' => '2px solid #ef4444', 'shadow' => '0 0 10px rgba(239,68,68,0.5), 0 0 20px rgba(249,115,22,0.25)'])],
            ['slug' => 'cadre-ocean',       'category' => 'frame', 'name' => 'Cadre Océan',         'description' => "Les profondeurs marines entourent ton profil d'un halo bleuté.",   'price' => 2000, 'sort_order' => 5,  'data' => json_encode(['border' => '2px solid #3b82f6', 'shadow' => '0 0 10px rgba(59,130,246,0.5), 0 0 20px rgba(6,182,212,0.2)'])],
            ['slug' => 'cadre-cristal',     'category' => 'frame', 'name' => 'Cadre Cristal',       'description' => 'Un cadre glacé aux reflets cristallins.',                         'price' => 2500, 'sort_order' => 6,  'data' => json_encode(['border' => '2px solid rgba(135,206,250,0.7)', 'shadow' => '0 0 12px rgba(135,206,250,0.4), inset 0 0 8px rgba(135,206,250,0.15)'])],
            ['slug' => 'cadre-electrik',    'category' => 'frame', 'name' => 'Cadre Électrik',      'description' => 'Des éclairs crépitent autour de ton avatar.',                     'price' => 2500, 'sort_order' => 7,  'data' => json_encode(['border' => '2px solid #eab308', 'shadow' => '0 0 12px rgba(234,179,8,0.5), 0 0 24px rgba(250,204,21,0.25)', 'animation' => 'true'])],
            ['slug' => 'cadre-holographique','category' => 'frame', 'name' => 'Cadre Holographique','description' => "Un cadre arc-en-ciel qui brille de mille feux.",                  'price' => 3000, 'sort_order' => 8,  'data' => json_encode(['border' => '2px solid #a382ff', 'shadow' => '0 0 12px rgba(163,130,255,0.4), 0 0 24px rgba(255,107,107,0.15)', 'animation' => 'true'])],
            ['slug' => 'cadre-tenebres',    'category' => 'frame', 'name' => 'Cadre Ténèbres',      'description' => 'Une aura sombre et menaçante enveloppe ton profil.',              'price' => 3500, 'sort_order' => 9,  'data' => json_encode(['border' => '2px solid #7c3aed', 'shadow' => '0 0 14px rgba(124,58,237,0.5), 0 0 28px rgba(88,28,135,0.3)', 'animation' => 'true'])],
            ['slug' => 'cadre-legendaire',  'category' => 'frame', 'name' => 'Cadre Légendaire',    'description' => "Le cadre le plus impressionnant — or et violet mystique.",         'price' => 5000, 'sort_order' => 10, 'data' => json_encode(['border' => '2px solid #ffd700', 'shadow' => '0 0 16px rgba(255,215,0,0.5), 0 0 32px rgba(163,130,255,0.3)', 'animation' => 'true'])],

            // ═══ SLEEVES ══════════════════════════════════════════════
            ['slug' => 'sleeve-standard',          'category' => 'sleeve', 'name' => 'Sleeve Standard',         'description' => 'Le dos de carte classique.',                                  'price' => 0,    'sort_order' => 1,  'data' => json_encode(['background' => 'linear-gradient(135deg, #1a1d25, #2a2d35)', 'pattern' => 'none', 'color' => '#6B7280'])],
            ['slug' => 'sleeve-flagada',           'category' => 'sleeve', 'name' => 'Sleeve Ramoloss',         'description' => 'Ramoloss flotte paisiblement dans une eau cristalline.',       'price' => 1000, 'sort_order' => 2,  'data' => json_encode(['image' => 'images/sleeves/flagada.jpg', 'color' => '#f472b6'])],
            ['slug' => 'sleeve-noctali',           'category' => 'sleeve', 'name' => 'Sleeve Noctali',          'description' => 'Noctali se repose à l\'ombre d\'un après-midi paisible.',      'price' => 1500, 'sort_order' => 3,  'data' => json_encode(['image' => 'images/sleeves/noctali.jpg', 'color' => '#fbbf24'])],
            ['slug' => 'sleeve-altaria',           'category' => 'sleeve', 'name' => 'Sleeve Altaria',          'description' => 'Altaria plane dans un ciel de nuages dorés au crépuscule.',    'price' => 1500, 'sort_order' => 4,  'data' => json_encode(['image' => 'images/sleeves/altaria.jpg', 'color' => '#93c5fd'])],
            ['slug' => 'sleeve-dragonfly',         'category' => 'sleeve', 'name' => 'Sleeve Libégon',          'description' => 'Libégon contemple l\'horizon sous un ciel immense.',           'price' => 2000, 'sort_order' => 5,  'data' => json_encode(['image' => 'images/sleeves/dragonfly.jpg', 'color' => '#86efac'])],
            ['slug' => 'sleeve-lucario',           'category' => 'sleeve', 'name' => 'Sleeve Lucario',          'description' => 'Lucario médite dans le silence d\'une forêt enneigée.',        'price' => 2500, 'sort_order' => 6,  'data' => json_encode(['image' => 'images/sleeves/lucario.jpg', 'color' => '#93c5fd'])],
            ['slug' => 'sleeve-dracolosse',        'category' => 'sleeve', 'name' => 'Sleeve Dracolosse',       'description' => 'Dracolosse s\'envole devant la pleine lune.',                  'price' => 3000, 'sort_order' => 7,  'data' => json_encode(['image' => 'images/sleeves/dracolosse.jpg', 'color' => '#fdba74'])],
            ['slug' => 'sleeve-lugia',             'category' => 'sleeve', 'name' => 'Sleeve Lugia',            'description' => 'Lugia survole les îles dans un ciel sans limites.',             'price' => 3500, 'sort_order' => 8,  'data' => json_encode(['image' => 'images/sleeves/lugia.jpg', 'color' => '#e0e7ff'])],
            ['slug' => 'sleeve-dracolosseetraquaza','category' => 'sleeve', 'name' => 'Sleeve Duo Légendaire', 'description' => 'Dracolosse et Rayquaza, maîtres du ciel, unis dans les nuages.','price' => 4000, 'sort_order' => 9,  'data' => json_encode(['image' => 'images/sleeves/dracolosseetraquaza.jpg', 'color' => '#c4b5fd'])],
            ['slug' => 'sleeve-vague',             'category' => 'sleeve', 'name' => 'Sleeve Grande Vague',     'description' => 'Pikachu et ses amis surfent sur la Grande Vague.',              'price' => 5000, 'sort_order' => 10, 'data' => json_encode(['image' => 'images/sleeves/vague.jpg', 'color' => '#60a5fa'])],
        ];

        $now = now();

        foreach ($items as $item) {
            DB::table('shop_items')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'preview'    => '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        // Clean up old sleeve slugs that no longer exist
        $currentSlugs = array_column($items, 'slug');
        DB::table('shop_items')
            ->where('category', 'sleeve')
            ->whereNotIn('slug', $currentSlugs)
            ->delete();
    }

    public function down(): void
    {
        DB::table('shop_items')->whereIn('category', ['title', 'frame', 'sleeve'])->delete();
    }
};
