<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $items = [
            // ═══ TITLES ═══════════════════════════════════════════════
            ['slug' => 'dresseur-novice',     'category' => 'title', 'name' => 'Dresseur Novice',          'description' => 'Le titre de base pour tout dresseur débutant.',                'price' => 0,     'sort_order' => 1,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #9CA3AF, #6B7280)', 'shadow' => '0 2px 6px rgba(156,163,175,0.3)'])],
            ['slug' => 'meilleur-dresseur',   'category' => 'title', 'name' => 'Meilleur Dresseur',        'description' => 'Prouve ta valeur avec ce titre doré prestigieux.',              'price' => 2000,  'sort_order' => 2,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #ffd700, #f59e0b, #d4a843)', 'shadow' => '0 2px 10px rgba(255,215,0,0.5)'])],
            ['slug' => 'champion-arene',      'category' => 'title', 'name' => "Champion d'Arène",         'description' => "Un titre ardent pour les combattants d'élite.",                 'price' => 3000,  'sort_order' => 3,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #ef4444, #f59e0b)', 'shadow' => '0 2px 10px rgba(239,68,68,0.4)'])],
            ['slug' => 'rival-eternel',       'category' => 'title', 'name' => 'Rival Éternel',            'description' => 'Pour ceux qui affrontent sans relâche leur destin.',            'price' => 2500,  'sort_order' => 4,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #3b82f6, #ef4444)', 'shadow' => '0 2px 10px rgba(59,130,246,0.4)'])],
            ['slug' => 'chasseur-shinies',    'category' => 'title', 'name' => 'Chasseur de Shinies',      'description' => 'Pour ceux qui traquent les cartes les plus rares.',             'price' => 3500,  'sort_order' => 5,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #f0e68c, #ffd700, #daa520)', 'shadow' => '0 2px 10px rgba(255,215,0,0.5)'])],
            ['slug' => 'collectionneur-elite','category' => 'title', 'name' => "Collectionneur d'Élite",   'description' => 'Réservé aux collectionneurs les plus dévoués.',                 'price' => 4000,  'sort_order' => 6,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #22c55e, #3b82f6)', 'shadow' => '0 2px 10px rgba(34,197,94,0.4)'])],
            ['slug' => 'gardien-aura',        'category' => 'title', 'name' => "Gardien de l'Aura",        'description' => "Canalise l'énergie mystique qui entoure toute chose.",           'price' => 4500,  'sort_order' => 7,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #06b6d4, #8b5cf6, #ec4899)', 'shadow' => '0 2px 12px rgba(139,92,246,0.45)'])],
            ['slug' => 'maitre-ligue',        'category' => 'title', 'name' => 'Maître de la Ligue',       'description' => 'Le titre ultime — un gradient mystique et doré.',               'price' => 5000,  'sort_order' => 8,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #a382ff, #ffd700)', 'shadow' => '0 2px 12px rgba(163,130,255,0.5)'])],
            ['slug' => 'roi-des-dragons',     'category' => 'title', 'name' => 'Roi des Dragons',          'description' => 'Domine le ciel comme un maître draconier.',                    'price' => 6000,  'sort_order' => 9,  'data' => json_encode(['gradient' => 'linear-gradient(135deg, #7c3aed, #2563eb, #06b6d4)', 'shadow' => '0 2px 14px rgba(124,58,237,0.5)'])],
            ['slug' => 'legende-vivante',     'category' => 'title', 'name' => 'Légende Vivante',          'description' => 'Le titre le plus rare et le plus convoité de tout PokeHub.',   'price' => 10000, 'sort_order' => 10, 'data' => json_encode(['gradient' => 'linear-gradient(135deg, #ffd700, #ff6b6b, #a382ff, #ffd700)', 'shadow' => '0 3px 16px rgba(255,215,0,0.6)'])],

            // ═══ FRAMES ═══════════════════════════════════════════════
            ['slug' => 'cadre-classique',  'category' => 'frame', 'name' => 'Cadre Classique',     'description' => 'Le cadre par défaut, simple et sobre.',                           'price' => 0,    'sort_order' => 1,  'data' => json_encode(['border' => '2px solid var(--border)', 'shadow' => 'none'])],
            ['slug' => 'cadre-dore',       'category' => 'frame', 'name' => 'Cadre Doré',          'description' => "Un cadre doré élégant autour de ton avatar.",                      'price' => 1500, 'sort_order' => 2,  'data' => json_encode(['border' => '2px solid #ffd700', 'shadow' => '0 0 12px rgba(255,215,0,0.4), inset 0 0 6px rgba(255,215,0,0.1)'])],
            ['slug' => 'cadre-nature',     'category' => 'frame', 'name' => 'Cadre Sylvestre',     'description' => 'Un cadre verdoyant inspiré de la forêt de Jade.',                 'price' => 1800, 'sort_order' => 3,  'data' => json_encode(['border' => '2px solid #22c55e', 'shadow' => '0 0 10px rgba(34,197,94,0.45), 0 0 18px rgba(16,185,129,0.2)'])],
            ['slug' => 'cadre-feu',        'category' => 'frame', 'name' => 'Cadre Flamme',        'description' => "Un cadre ardent qui brûle d'intensité autour de ton avatar.",      'price' => 2000, 'sort_order' => 4,  'data' => json_encode(['border' => '2px solid #ef4444', 'shadow' => '0 0 10px rgba(239,68,68,0.5), 0 0 20px rgba(249,115,22,0.25)'])],
            ['slug' => 'cadre-ocean',      'category' => 'frame', 'name' => 'Cadre Océan',         'description' => "Les profondeurs marines entourent ton profil d'un halo bleuté.",   'price' => 2000, 'sort_order' => 5,  'data' => json_encode(['border' => '2px solid #3b82f6', 'shadow' => '0 0 10px rgba(59,130,246,0.5), 0 0 20px rgba(6,182,212,0.2)'])],
            ['slug' => 'cadre-cristal',    'category' => 'frame', 'name' => 'Cadre Cristal',       'description' => 'Un cadre glacé aux reflets cristallins.',                         'price' => 2500, 'sort_order' => 6,  'data' => json_encode(['border' => '2px solid rgba(135,206,250,0.7)', 'shadow' => '0 0 12px rgba(135,206,250,0.4), inset 0 0 8px rgba(135,206,250,0.15)'])],
            ['slug' => 'cadre-electrik',   'category' => 'frame', 'name' => 'Cadre Électrik',      'description' => 'Des éclairs crépitent autour de ton avatar.',                     'price' => 2500, 'sort_order' => 7,  'data' => json_encode(['border' => '2px solid #eab308', 'shadow' => '0 0 12px rgba(234,179,8,0.5), 0 0 24px rgba(250,204,21,0.25)', 'animation' => 'true'])],
            ['slug' => 'cadre-holographique','category' => 'frame', 'name' => 'Cadre Holographique','description' => "Un cadre arc-en-ciel qui brille de mille feux.",                  'price' => 3000, 'sort_order' => 8,  'data' => json_encode(['border' => '2px solid #a382ff', 'shadow' => '0 0 12px rgba(163,130,255,0.4), 0 0 24px rgba(255,107,107,0.15)', 'animation' => 'true'])],
            ['slug' => 'cadre-tenebres',   'category' => 'frame', 'name' => 'Cadre Ténèbres',      'description' => 'Une aura sombre et menaçante enveloppe ton profil.',              'price' => 3500, 'sort_order' => 9,  'data' => json_encode(['border' => '2px solid #7c3aed', 'shadow' => '0 0 14px rgba(124,58,237,0.5), 0 0 28px rgba(88,28,135,0.3)', 'animation' => 'true'])],
            ['slug' => 'cadre-legendaire', 'category' => 'frame', 'name' => 'Cadre Légendaire',    'description' => "Le cadre le plus impressionnant — or et violet mystique.",         'price' => 5000, 'sort_order' => 10, 'data' => json_encode(['border' => '2px solid #ffd700', 'shadow' => '0 0 16px rgba(255,215,0,0.5), 0 0 32px rgba(163,130,255,0.3)', 'animation' => 'true'])],

            // ═══ SLEEVES ══════════════════════════════════════════════
            ['slug' => 'sleeve-standard',   'category' => 'sleeve', 'name' => 'Sleeve Standard',   'description' => 'Le dos de carte classique.',                                      'price' => 0,    'sort_order' => 1,  'data' => json_encode(['background' => 'linear-gradient(135deg, #1a1d25, #2a2d35)', 'pattern' => 'none', 'color' => '#6B7280'])],
            ['slug' => 'sleeve-pokeball',   'category' => 'sleeve', 'name' => 'Sleeve Pokéball',   'description' => "Un dos de carte aux couleurs de la Pokéball classique.",            'price' => 1000, 'sort_order' => 2,  'data' => json_encode(['background' => 'linear-gradient(180deg, #ef4444 0%, #ef4444 45%, #333 45%, #333 55%, #fff 55%, #fff 100%)', 'color' => '#ef4444'])],
            ['slug' => 'sleeve-pikachu',    'category' => 'sleeve', 'name' => 'Sleeve Pikachu',    'description' => "Un design éclatant aux couleurs de Pikachu.",                      'price' => 1500, 'sort_order' => 3,  'data' => json_encode(['background' => 'linear-gradient(135deg, #fbbf24, #f59e0b, #eab308)', 'color' => '#f59e0b'])],
            ['slug' => 'sleeve-evoli',      'category' => 'sleeve', 'name' => 'Sleeve Évoli',      'description' => "Un dos de carte chaleureux aux teintes d'Évoli.",                  'price' => 1500, 'sort_order' => 4,  'data' => json_encode(['background' => 'linear-gradient(135deg, #92400e, #b45309, #d97706)', 'color' => '#d97706'])],
            ['slug' => 'sleeve-ultraball',  'category' => 'sleeve', 'name' => 'Sleeve Ultra Ball', 'description' => 'Le design noir et jaune de la Ultra Ball.',                        'price' => 2000, 'sort_order' => 5,  'data' => json_encode(['background' => 'linear-gradient(135deg, #1a1a2e, #16213e, #0f3460)', 'accent' => 'linear-gradient(135deg, #eab308, #f59e0b)', 'color' => '#eab308'])],
            ['slug' => 'sleeve-leviator',   'category' => 'sleeve', 'name' => 'Sleeve Léviator',   'description' => 'La fureur des mers déchaînées sur tes cartes.',                    'price' => 2500, 'sort_order' => 6,  'data' => json_encode(['background' => 'linear-gradient(135deg, #0c4a6e, #0369a1, #0ea5e9)', 'accent' => 'linear-gradient(135deg, #38bdf8, #7dd3fc)', 'color' => '#0ea5e9'])],
            ['slug' => 'sleeve-dracaufeu',  'category' => 'sleeve', 'name' => 'Sleeve Dracaufeu',  'description' => "Un dos de carte enflammé inspiré du légendaire Dracaufeu.",         'price' => 3000, 'sort_order' => 7,  'data' => json_encode(['background' => 'linear-gradient(135deg, #dc2626, #f97316, #fbbf24)', 'accent' => 'linear-gradient(135deg, #fbbf24, #f59e0b)', 'color' => '#ef4444'])],
            ['slug' => 'sleeve-mewtwo',     'category' => 'sleeve', 'name' => 'Sleeve Mewtwo',     'description' => "L'énergie psychique de Mewtwo se reflète sur ce dos de carte.",     'price' => 3500, 'sort_order' => 8,  'data' => json_encode(['background' => 'linear-gradient(135deg, #4c1d95, #7c3aed, #a78bfa)', 'accent' => 'linear-gradient(135deg, #c4b5fd, #e9d5ff)', 'color' => '#8b5cf6'])],
            ['slug' => 'sleeve-masterball', 'category' => 'sleeve', 'name' => 'Sleeve Master Ball','description' => "Le dos de carte légendaire — violet et élégant.",                   'price' => 4000, 'sort_order' => 9,  'data' => json_encode(['background' => 'linear-gradient(135deg, #581c87, #7c3aed, #a855f7)', 'accent' => 'linear-gradient(135deg, #e879f9, #f0abfc)', 'color' => '#a855f7'])],
            ['slug' => 'sleeve-rayquaza',   'category' => 'sleeve', 'name' => 'Sleeve Rayquaza',   'description' => "Le seigneur du ciel orne le dos de tes cartes d'émeraude.",         'price' => 5000, 'sort_order' => 10, 'data' => json_encode(['background' => 'linear-gradient(135deg, #064e3b, #059669, #34d399)', 'accent' => 'linear-gradient(135deg, #fbbf24, #f59e0b)', 'color' => '#10b981'])],
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
    }

    public function down(): void
    {
        DB::table('shop_items')->whereIn('category', ['title', 'frame', 'sleeve'])->delete();
    }
};
