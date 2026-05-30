<?php

namespace Database\Seeders;

use App\Models\ShopItem;
use Illuminate\Database\Seeder;

class ShopItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // ═══ TITLES ═══════════════════════════════════════════════
            [
                'slug'        => 'dresseur-novice',
                'category'    => 'title',
                'name'        => 'Dresseur Novice',
                'description' => 'Le titre de base pour tout dresseur débutant.',
                'price'       => 0,
                'preview'     => '',
                'sort_order'  => 1,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #9CA3AF, #6B7280)',
                    'shadow'   => 'drop-shadow(0 1px 3px rgba(156,163,175,0.3))',
                ],
            ],
            [
                'slug'        => 'meilleur-dresseur',
                'category'    => 'title',
                'name'        => 'Meilleur Dresseur',
                'description' => 'Prouve ta valeur avec ce titre doré prestigieux.',
                'price'       => 2000,
                'preview'     => '',
                'sort_order'  => 2,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #bf953f, #fcf6ba, #d4a843, #fcf6ba, #bf953f)',
                    'shadow'   => 'drop-shadow(0 0 6px rgba(255,215,0,0.6)) drop-shadow(0 2px 4px rgba(191,149,63,0.4))',
                ],
            ],
            [
                'slug'        => 'champion-arene',
                'category'    => 'title',
                'name'        => 'Champion d\'Arène',
                'description' => 'Un titre de braise pour les combattants d\'élite.',
                'price'       => 3000,
                'preview'     => '',
                'sort_order'  => 3,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #ff4500, #ff6347, #ffd700, #ff6347, #ff4500)',
                    'shadow'   => 'drop-shadow(0 0 8px rgba(255,69,0,0.6)) drop-shadow(0 0 16px rgba(255,165,0,0.3))',
                ],
            ],
            [
                'slug'        => 'rival-eternel',
                'category'    => 'title',
                'name'        => 'Rival Éternel',
                'description' => 'Le feu et la glace s\'affrontent dans ce titre bicolore.',
                'price'       => 2500,
                'preview'     => '',
                'sort_order'  => 4,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #3b82f6, #6366f1, #a855f7, #ec4899, #ef4444)',
                    'shadow'   => 'drop-shadow(-2px 0 6px rgba(59,130,246,0.5)) drop-shadow(2px 0 6px rgba(239,68,68,0.5))',
                ],
            ],
            [
                'slug'        => 'chasseur-shinies',
                'category'    => 'title',
                'name'        => 'Chasseur de Shinies',
                'description' => 'Un éclat scintillant pour les traqueurs de raretés.',
                'price'       => 3500,
                'preview'     => '',
                'sort_order'  => 5,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #ffd700, #fffacd, #ffd700, #fff8dc, #ffd700, #fffacd)',
                    'shadow'   => 'drop-shadow(0 0 4px rgba(255,250,205,0.8)) drop-shadow(0 0 12px rgba(255,215,0,0.5)) drop-shadow(0 0 20px rgba(255,215,0,0.2))',
                ],
            ],
            [
                'slug'        => 'collectionneur-elite',
                'category'    => 'title',
                'name'        => 'Collectionneur d\'Élite',
                'description' => 'Un dégradé émeraude pour les collectionneurs dévoués.',
                'price'       => 4000,
                'preview'     => '',
                'sort_order'  => 6,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #059669, #34d399, #a7f3d0, #34d399, #059669)',
                    'shadow'   => 'drop-shadow(0 0 8px rgba(52,211,153,0.5)) drop-shadow(0 2px 6px rgba(5,150,105,0.4))',
                ],
            ],
            [
                'slug'        => 'gardien-aura',
                'category'    => 'title',
                'name'        => 'Gardien de l\'Aura',
                'description' => 'Triple halo néon : cyan, violet et rose fusionnent.',
                'price'       => 4500,
                'preview'     => '',
                'sort_order'  => 7,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #06b6d4, #8b5cf6, #ec4899, #8b5cf6, #06b6d4)',
                    'shadow'   => 'drop-shadow(0 0 6px rgba(6,182,212,0.5)) drop-shadow(0 0 6px rgba(139,92,246,0.5)) drop-shadow(0 0 6px rgba(236,72,153,0.5))',
                ],
            ],
            [
                'slug'        => 'maitre-ligue',
                'category'    => 'title',
                'name'        => 'Maître de la Ligue',
                'description' => 'Aura royale — or et pourpre entrelacés.',
                'price'       => 5000,
                'preview'     => '',
                'sort_order'  => 8,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #ffd700, #a382ff, #ffd700, #a382ff, #ffd700)',
                    'shadow'   => 'drop-shadow(0 0 8px rgba(163,130,255,0.6)) drop-shadow(0 0 16px rgba(255,215,0,0.4)) drop-shadow(0 2px 4px rgba(0,0,0,0.3))',
                ],
            ],
            [
                'slug'        => 'roi-des-dragons',
                'category'    => 'title',
                'name'        => 'Roi des Dragons',
                'description' => 'La puissance draconique irradie de chaque lettre.',
                'price'       => 6000,
                'preview'     => '',
                'sort_order'  => 9,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #1e3a8a, #7c3aed, #06b6d4, #10b981, #06b6d4, #7c3aed)',
                    'shadow'   => 'drop-shadow(0 0 10px rgba(124,58,237,0.6)) drop-shadow(0 0 20px rgba(6,182,212,0.3)) drop-shadow(0 3px 6px rgba(0,0,0,0.4))',
                ],
            ],
            [
                'slug'        => 'legende-vivante',
                'category'    => 'title',
                'name'        => 'Légende Vivante',
                'description' => 'Prismatique — toutes les couleurs de l\'arc-en-ciel.',
                'price'       => 10000,
                'preview'     => '',
                'sort_order'  => 10,
                'data'        => [
                    'gradient' => 'linear-gradient(90deg, #ff0000, #ff8c00, #ffd700, #00ff88, #00bfff, #8b5cf6, #ff0080, #ff0000)',
                    'shadow'   => 'drop-shadow(0 0 6px rgba(255,0,0,0.35)) drop-shadow(0 0 6px rgba(0,191,255,0.35)) drop-shadow(0 0 6px rgba(139,92,246,0.35)) drop-shadow(0 0 14px rgba(255,215,0,0.4))',
                ],
            ],
            // Level 10 reward title
            [
                'slug'        => 'chasseur',
                'category'    => 'title',
                'name'        => 'Chasseur',
                'description' => 'Récompense niveau 10 — Titre épique.',
                'price'       => 0,
                'preview'     => '',
                'sort_order'  => 99,
                'data'        => [
                    'gradient'     => 'linear-gradient(135deg, #a855f7, #ffffff, #c084fc)',
                    'shadow'       => 'drop-shadow(0 0 6px rgba(168,85,247,0.5)) drop-shadow(0 0 12px rgba(168,85,247,0.3))',
                    'animated'     => true,
                    'level_reward' => 10,
                ],
            ],

            // ═══ FRAMES ═══════════════════════════════════════════════
            [
                'slug' => 'cadre-classique', 'category' => 'frame', 'name' => 'Cadre Classique',
                'description' => 'Le cadre par défaut, simple et sobre.', 'price' => 0, 'preview' => '', 'sort_order' => 1,
                'data' => ['border' => '2px solid var(--border)', 'shadow' => 'none'],
            ],
            [
                'slug' => 'cadre-dore', 'category' => 'frame', 'name' => 'Cadre Doré',
                'description' => 'Un cadre doré élégant autour de ton avatar.', 'price' => 1500, 'preview' => '', 'sort_order' => 2,
                'data' => ['border' => '2px solid #ffd700', 'shadow' => '0 0 12px rgba(255,215,0,0.4), inset 0 0 6px rgba(255,215,0,0.1)'],
            ],
            [
                'slug' => 'cadre-nature', 'category' => 'frame', 'name' => 'Cadre Sylvestre',
                'description' => 'Un cadre verdoyant inspiré de la forêt de Jade.', 'price' => 1800, 'preview' => '', 'sort_order' => 3,
                'data' => ['border' => '2px solid #22c55e', 'shadow' => '0 0 10px rgba(34,197,94,0.45), 0 0 18px rgba(16,185,129,0.2)'],
            ],
            [
                'slug' => 'cadre-feu', 'category' => 'frame', 'name' => 'Cadre Flamme',
                'description' => 'Un cadre ardent autour de ton avatar.', 'price' => 2000, 'preview' => '', 'sort_order' => 4,
                'data' => ['border' => '2px solid #ef4444', 'shadow' => '0 0 10px rgba(239,68,68,0.5), 0 0 20px rgba(249,115,22,0.25)'],
            ],
            [
                'slug' => 'cadre-ocean', 'category' => 'frame', 'name' => 'Cadre Océan',
                'description' => 'Les profondeurs marines entourent ton profil.', 'price' => 2000, 'preview' => '', 'sort_order' => 5,
                'data' => ['border' => '2px solid #3b82f6', 'shadow' => '0 0 10px rgba(59,130,246,0.5), 0 0 20px rgba(6,182,212,0.2)'],
            ],
            [
                'slug' => 'cadre-cristal', 'category' => 'frame', 'name' => 'Cadre Cristal',
                'description' => 'Un cadre glacé aux reflets cristallins.', 'price' => 2500, 'preview' => '', 'sort_order' => 6,
                'data' => ['border' => '2px solid rgba(135,206,250,0.7)', 'shadow' => '0 0 12px rgba(135,206,250,0.4), inset 0 0 8px rgba(135,206,250,0.15)'],
            ],
            [
                'slug' => 'cadre-electrik', 'category' => 'frame', 'name' => 'Cadre Électrik',
                'description' => 'Des éclairs crépitent autour de ton avatar.', 'price' => 2500, 'preview' => '', 'sort_order' => 7,
                'data' => ['border' => '2px solid #eab308', 'shadow' => '0 0 12px rgba(234,179,8,0.5), 0 0 24px rgba(250,204,21,0.25)', 'animation' => 'true'],
            ],
            [
                'slug' => 'cadre-holographique', 'category' => 'frame', 'name' => 'Cadre Holographique',
                'description' => 'Un cadre arc-en-ciel qui brille.', 'price' => 3000, 'preview' => '', 'sort_order' => 8,
                'data' => ['border' => '2px solid #a382ff', 'shadow' => '0 0 12px rgba(163,130,255,0.4), 0 0 24px rgba(255,107,107,0.15)', 'animation' => 'true'],
            ],
            [
                'slug' => 'cadre-tenebres', 'category' => 'frame', 'name' => 'Cadre Ténèbres',
                'description' => 'Une aura sombre enveloppe ton profil.', 'price' => 3500, 'preview' => '', 'sort_order' => 9,
                'data' => ['border' => '2px solid #7c3aed', 'shadow' => '0 0 14px rgba(124,58,237,0.5), 0 0 28px rgba(88,28,135,0.3)', 'animation' => 'true'],
            ],
            [
                'slug' => 'cadre-legendaire', 'category' => 'frame', 'name' => 'Cadre Légendaire',
                'description' => 'Or et violet mystique.', 'price' => 5000, 'preview' => '', 'sort_order' => 10,
                'data' => ['border' => '2px solid #ffd700', 'shadow' => '0 0 16px rgba(255,215,0,0.5), 0 0 32px rgba(163,130,255,0.3)', 'animation' => 'true'],
            ],

            // ═══ SLEEVES ══════════════════════════════════════════════
            [
                'slug' => 'sleeve-standard', 'category' => 'sleeve', 'name' => 'Sleeve Standard',
                'description' => 'Le dos de carte classique.', 'price' => 0, 'preview' => '', 'sort_order' => 1,
                'data' => ['background' => 'linear-gradient(135deg, #1a1d25, #2a2d35)', 'pattern' => 'none', 'color' => '#6B7280'],
            ],
            [
                'slug' => 'sleeve-flagada', 'category' => 'sleeve', 'name' => 'Sleeve Ramoloss',
                'description' => 'Ramoloss flotte paisiblement dans une eau cristalline.', 'price' => 1000, 'preview' => '', 'sort_order' => 2,
                'data' => ['image' => 'images/sleeves/flagada.jpg', 'color' => '#f472b6'],
            ],
            [
                'slug' => 'sleeve-noctali', 'category' => 'sleeve', 'name' => 'Sleeve Noctali',
                'description' => 'Noctali se repose à l\'ombre d\'un après-midi paisible.', 'price' => 1500, 'preview' => '', 'sort_order' => 3,
                'data' => ['image' => 'images/sleeves/noctali.jpg', 'color' => '#fbbf24'],
            ],
            [
                'slug' => 'sleeve-altaria', 'category' => 'sleeve', 'name' => 'Sleeve Altaria',
                'description' => 'Altaria plane dans un ciel de nuages dorés au crépuscule.', 'price' => 1500, 'preview' => '', 'sort_order' => 4,
                'data' => ['image' => 'images/sleeves/altaria.jpg', 'color' => '#93c5fd'],
            ],
            [
                'slug' => 'sleeve-dragonfly', 'category' => 'sleeve', 'name' => 'Sleeve Libégon',
                'description' => 'Libégon contemple l\'horizon sous un ciel immense.', 'price' => 2000, 'preview' => '', 'sort_order' => 5,
                'data' => ['image' => 'images/sleeves/dragonfly.jpg', 'color' => '#86efac'],
            ],
            [
                'slug' => 'sleeve-lucario', 'category' => 'sleeve', 'name' => 'Sleeve Lucario',
                'description' => 'Lucario médite dans le silence d\'une forêt enneigée.', 'price' => 2500, 'preview' => '', 'sort_order' => 6,
                'data' => ['image' => 'images/sleeves/lucario.jpg', 'color' => '#93c5fd'],
            ],
            [
                'slug' => 'sleeve-dracolosse', 'category' => 'sleeve', 'name' => 'Sleeve Dracolosse',
                'description' => 'Dracolosse s\'envole devant la pleine lune.', 'price' => 3000, 'preview' => '', 'sort_order' => 7,
                'data' => ['image' => 'images/sleeves/dracolosse.jpg', 'color' => '#fdba74'],
            ],
            [
                'slug' => 'sleeve-lugia', 'category' => 'sleeve', 'name' => 'Sleeve Lugia',
                'description' => 'Lugia survole les îles dans un ciel sans limites.', 'price' => 3500, 'preview' => '', 'sort_order' => 8,
                'data' => ['image' => 'images/sleeves/lugia.jpg', 'color' => '#e0e7ff'],
            ],
            [
                'slug' => 'sleeve-dracolosseetraquaza', 'category' => 'sleeve', 'name' => 'Sleeve Duo Légendaire',
                'description' => 'Dracolosse et Rayquaza, maîtres du ciel, unis dans les nuages.', 'price' => 4000, 'preview' => '', 'sort_order' => 9,
                'data' => ['image' => 'images/sleeves/dracolosseetraquaza.jpg', 'color' => '#c4b5fd'],
            ],
            [
                'slug' => 'sleeve-vague', 'category' => 'sleeve', 'name' => 'Sleeve Grande Vague',
                'description' => 'Pikachu et ses amis surfent sur la Grande Vague.', 'price' => 5000, 'preview' => '', 'sort_order' => 10,
                'data' => ['image' => 'images/sleeves/vague.jpg', 'color' => '#60a5fa'],
            ],
            // New regular sleeves
            [
                'slug' => 'sleeve-ghetis', 'category' => 'sleeve', 'name' => 'Ghetis',
                'description' => 'Le leader de la Team Plasma.', 'price' => 2000, 'preview' => '', 'sort_order' => 15,
                'data' => ['image' => 'images/sleeves/ghetis.jpg', 'color' => '#6B21A8'],
            ],
            [
                'slug' => 'sleeve-mewtwo-mew', 'category' => 'sleeve', 'name' => 'Mewtwo & Mew',
                'description' => 'Le duo psychique légendaire.', 'price' => 3500, 'preview' => '', 'sort_order' => 16,
                'data' => ['image' => 'images/sleeves/mewtwo_mew.jpg', 'color' => '#A855F7'],
            ],
            [
                'slug' => 'sleeve-torterra', 'category' => 'sleeve', 'name' => 'Torterra',
                'description' => 'Le continent vivant, gardien de la nature.', 'price' => 1800, 'preview' => '', 'sort_order' => 17,
                'data' => ['image' => 'images/sleeves/torterra.jpg', 'color' => '#22c55e'],
            ],
            [
                'slug' => 'sleeve-osselet', 'category' => 'sleeve', 'name' => 'Osselait',
                'description' => 'Le petit guerrier solitaire.', 'price' => 1500, 'preview' => '', 'sort_order' => 18,
                'data' => ['image' => 'images/sleeves/osselet.jpg', 'color' => '#D4A843'],
            ],
            [
                'slug' => 'sleeve-ronflex', 'category' => 'sleeve', 'name' => 'Ronflex',
                'description' => 'Le géant endormi.', 'price' => 2500, 'preview' => '', 'sort_order' => 19,
                'data' => ['image' => 'images/sleeves/ronflex.jpg', 'color' => '#1E3A5F'],
            ],
            // Exclusive sleeves (gold tier)
            [
                'slug' => 'sleeve-palkia', 'category' => 'sleeve', 'name' => 'Palkia',
                'description' => 'Maître de l\'espace — Exclusif.', 'price' => 10000, 'preview' => '', 'sort_order' => 50,
                'data' => ['image' => 'images/sleeves/palkia.jpg', 'color' => '#FFD700', 'exclusive' => true],
            ],
            [
                'slug' => 'sleeve-dialga', 'category' => 'sleeve', 'name' => 'Dialga',
                'description' => 'Maître du temps — Exclusif.', 'price' => 10000, 'preview' => '', 'sort_order' => 51,
                'data' => ['image' => 'images/sleeves/dialga.jpg', 'color' => '#FFD700', 'exclusive' => true],
            ],
            [
                'slug' => 'sleeve-arceus', 'category' => 'sleeve', 'name' => 'Arceus',
                'description' => 'Le Dieu Pokémon — Exclusif.', 'price' => 10000, 'preview' => '', 'sort_order' => 52,
                'data' => ['image' => 'images/sleeves/arceus.jpg', 'color' => '#FFD700', 'exclusive' => true],
            ],
            [
                'slug' => 'sleeve-giratina', 'category' => 'sleeve', 'name' => 'Giratina',
                'description' => 'Le maître du Monde Distorsion — Exclusif.', 'price' => 10000, 'preview' => '', 'sort_order' => 53,
                'data' => ['image' => 'images/sleeves/giratina.jpg', 'color' => '#FFD700', 'exclusive' => true],
            ],

            // ═══ AVATARS ════════════════════════════════════════════════
            [
                'slug' => 'avatar-bulbizarre', 'category' => 'avatar', 'name' => 'Bulbizarre',
                'description' => 'Le starter plante originel.', 'price' => 0, 'preview' => '', 'sort_order' => 1,
                'data' => ['image' => 'images/pfp/bulbizarre.jpg'],
            ],
            [
                'slug' => 'avatar-charbambin', 'category' => 'avatar', 'name' => 'Charbambin',
                'description' => 'Le petit lézard de feu.', 'price' => 1000, 'preview' => '', 'sort_order' => 2,
                'data' => ['image' => 'images/pfp/charbambin.jpg'],
            ],
            [
                'slug' => 'avatar-poussacha', 'category' => 'avatar', 'name' => 'Poussacha',
                'description' => 'Le starter herbe de Paldea.', 'price' => 1200, 'preview' => '', 'sort_order' => 3,
                'data' => ['image' => 'images/pfp/poussacha.jpg'],
            ],
            [
                'slug' => 'avatar-amphinobi', 'category' => 'avatar', 'name' => 'Amphinobi',
                'description' => 'Le ninja aquatique.', 'price' => 1500, 'preview' => '', 'sort_order' => 4,
                'data' => ['image' => 'images/pfp/amphinobi.jpg'],
            ],
            [
                'slug' => 'avatar-arcanin', 'category' => 'avatar', 'name' => 'Arcanin',
                'description' => 'Le Pokémon légendaire du feu.', 'price' => 1500, 'preview' => '', 'sort_order' => 5,
                'data' => ['image' => 'images/pfp/arcanin.jpg'],
            ],
            [
                'slug' => 'avatar-locklass', 'category' => 'avatar', 'name' => 'Lokhlass',
                'description' => 'Le doux transporteur des mers.', 'price' => 1800, 'preview' => '', 'sort_order' => 6,
                'data' => ['image' => 'images/pfp/locklass.jpg'],
            ],
            [
                'slug' => 'avatar-lucario', 'category' => 'avatar', 'name' => 'Lucario',
                'description' => 'Le maître de l\'Aura.', 'price' => 2000, 'preview' => '', 'sort_order' => 7,
                'data' => ['image' => 'images/pfp/lucario.jpg'],
            ],
            [
                'slug' => 'avatar-draco', 'category' => 'avatar', 'name' => 'Draco',
                'description' => 'Le puissant dragon.', 'price' => 2000, 'preview' => '', 'sort_order' => 8,
                'data' => ['image' => 'images/pfp/draco.jpg'],
            ],
            [
                'slug' => 'avatar-dracolosse', 'category' => 'avatar', 'name' => 'Dracolosse',
                'description' => 'Le gardien bienveillant.', 'price' => 2500, 'preview' => '', 'sort_order' => 9,
                'data' => ['image' => 'images/pfp/dracolosse.jpg'],
            ],
            [
                'slug' => 'avatar-lagron', 'category' => 'avatar', 'name' => 'Galeking',
                'description' => 'Le titan d\'acier.', 'price' => 2500, 'preview' => '', 'sort_order' => 10,
                'data' => ['image' => 'images/pfp/lagron.jpg'],
            ],
            [
                'slug' => 'avatar-gardevoir-futur', 'category' => 'avatar', 'name' => 'Gardevoir Futur',
                'description' => 'La gardienne du futur.', 'price' => 3000, 'preview' => '', 'sort_order' => 11,
                'data' => ['image' => 'images/pfp/gardevoir_futur.jpg'],
            ],
            [
                'slug' => 'avatar-tyranocif', 'category' => 'avatar', 'name' => 'Tyranocif',
                'description' => 'Le destructeur des montagnes.', 'price' => 3000, 'preview' => '', 'sort_order' => 12,
                'data' => ['image' => 'images/pfp/tyranocif.jpg'],
            ],
            [
                'slug' => 'avatar-rayquaza', 'category' => 'avatar', 'name' => 'Rayquaza',
                'description' => 'Le gardien du ciel.', 'price' => 3500, 'preview' => '', 'sort_order' => 13,
                'data' => ['image' => 'images/pfp/rayquaza.jpg'],
            ],
            [
                'slug' => 'avatar-dracaufeu-shiny', 'category' => 'avatar', 'name' => 'Dracaufeu Shiny',
                'description' => 'Le dragon de feu en version chromatique.', 'price' => 4000, 'preview' => '', 'sort_order' => 14,
                'data' => ['image' => 'images/pfp/dracaufeu_shiny.jpg'],
            ],
            [
                'slug' => 'avatar-mega-dracaufeu-x', 'category' => 'avatar', 'name' => 'Méga-Dracaufeu X',
                'description' => 'La méga-évolution ultime.', 'price' => 5000, 'preview' => '', 'sort_order' => 15,
                'data' => ['image' => 'images/pfp/mega_dracaufeu_x.jpg'],
            ],
            [
                'slug' => 'avatar-deoxys', 'category' => 'avatar', 'name' => 'Deoxys',
                'description' => 'L\'entité extraterrestre — Exclusif.', 'price' => 8000, 'preview' => '', 'sort_order' => 50,
                'data' => ['image' => 'images/pfp/deoxys.jpg', 'exclusive' => true],
            ],
            [
                'slug' => 'avatar-giratina', 'category' => 'avatar', 'name' => 'Giratina',
                'description' => 'L\'ombre du Monde Distorsion — Exclusif.', 'price' => 8000, 'preview' => '', 'sort_order' => 51,
                'data' => ['image' => 'images/pfp/giratina.jpg', 'exclusive' => true],
            ],
            // Level 10 reward avatar
            [
                'slug' => 'avatar-dresseur-hisui', 'category' => 'avatar', 'name' => 'Dresseur de Hisui',
                'description' => 'Récompense niveau 10 — Épique.', 'price' => 0, 'preview' => '', 'sort_order' => 99,
                'data' => ['image' => 'images/pfp/dresseur_hisui.jpg', 'exclusive' => true, 'level_reward' => 10],
            ],
        ];

        foreach ($items as $item) {
            ShopItem::updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }

        // Clean up old slugs that are no longer in the items list
        $currentSlugs = array_column($items, 'slug');
        ShopItem::whereNotIn('slug', $currentSlugs)->delete();
    }
}
