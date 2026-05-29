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
                    'shadow'   => '0 2px 6px rgba(156,163,175,0.3)',
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
                    'gradient' => 'linear-gradient(135deg, #ffd700, #f59e0b, #d4a843)',
                    'shadow'   => '0 2px 10px rgba(255,215,0,0.5)',
                ],
            ],
            [
                'slug'        => 'champion-arene',
                'category'    => 'title',
                'name'        => 'Champion d\'Arène',
                'description' => 'Un titre ardent pour les combattants d\'élite.',
                'price'       => 3000,
                'preview'     => '',
                'sort_order'  => 3,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #ef4444, #f59e0b)',
                    'shadow'   => '0 2px 10px rgba(239,68,68,0.4)',
                ],
            ],
            [
                'slug'        => 'chasseur-shinies',
                'category'    => 'title',
                'name'        => 'Chasseur de Shinies',
                'description' => 'Pour ceux qui traquent les cartes les plus rares.',
                'price'       => 3500,
                'preview'     => '',
                'sort_order'  => 4,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #f0e68c, #ffd700, #daa520)',
                    'shadow'   => '0 2px 10px rgba(255,215,0,0.5)',
                ],
            ],
            [
                'slug'        => 'collectionneur-elite',
                'category'    => 'title',
                'name'        => 'Collectionneur d\'Élite',
                'description' => 'Réservé aux collectionneurs les plus dévoués.',
                'price'       => 4000,
                'preview'     => '',
                'sort_order'  => 5,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #22c55e, #3b82f6)',
                    'shadow'   => '0 2px 10px rgba(34,197,94,0.4)',
                ],
            ],
            [
                'slug'        => 'maitre-ligue',
                'category'    => 'title',
                'name'        => 'Maître de la Ligue',
                'description' => 'Le titre ultime — un gradient mystique et doré.',
                'price'       => 5000,
                'preview'     => '',
                'sort_order'  => 6,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #a382ff, #ffd700)',
                    'shadow'   => '0 2px 12px rgba(163,130,255,0.5)',
                ],
            ],
            [
                'slug'        => 'legende-vivante',
                'category'    => 'title',
                'name'        => 'Légende Vivante',
                'description' => 'Le titre le plus rare et le plus convoité de tout PokeHub.',
                'price'       => 10000,
                'preview'     => '',
                'sort_order'  => 7,
                'data'        => [
                    'gradient' => 'linear-gradient(135deg, #ffd700, #ff6b6b, #a382ff, #ffd700)',
                    'shadow'   => '0 3px 16px rgba(255,215,0,0.6)',
                ],
            ],

            // ═══ FRAMES ═══════════════════════════════════════════════
            [
                'slug'        => 'cadre-classique',
                'category'    => 'frame',
                'name'        => 'Cadre Classique',
                'description' => 'Le cadre par défaut, simple et sobre.',
                'price'       => 0,
                'preview'     => '',
                'sort_order'  => 1,
                'data'        => [
                    'border' => '2px solid var(--border)',
                    'shadow' => 'none',
                ],
            ],
            [
                'slug'        => 'cadre-dore',
                'category'    => 'frame',
                'name'        => 'Cadre Doré',
                'description' => 'Un cadre doré élégant autour de ton avatar.',
                'price'       => 1500,
                'preview'     => '',
                'sort_order'  => 2,
                'data'        => [
                    'border' => '2px solid #ffd700',
                    'shadow' => '0 0 12px rgba(255,215,0,0.4), inset 0 0 6px rgba(255,215,0,0.1)',
                ],
            ],
            [
                'slug'        => 'cadre-holographique',
                'category'    => 'frame',
                'name'        => 'Cadre Holographique',
                'description' => 'Un cadre arc-en-ciel qui brille de mille feux.',
                'price'       => 3000,
                'preview'     => '',
                'sort_order'  => 3,
                'data'        => [
                    'border' => '2px solid #a382ff',
                    'shadow' => '0 0 12px rgba(163,130,255,0.4), 0 0 24px rgba(255,107,107,0.15)',
                    'animation' => 'true',
                ],
            ],
            [
                'slug'        => 'cadre-cristal',
                'category'    => 'frame',
                'name'        => 'Cadre Cristal',
                'description' => 'Un cadre glacé aux reflets cristallins.',
                'price'       => 2500,
                'preview'     => '',
                'sort_order'  => 4,
                'data'        => [
                    'border' => '2px solid rgba(135,206,250,0.7)',
                    'shadow' => '0 0 12px rgba(135,206,250,0.4), inset 0 0 8px rgba(135,206,250,0.15)',
                ],
            ],
            [
                'slug'        => 'cadre-legendaire',
                'category'    => 'frame',
                'name'        => 'Cadre Légendaire',
                'description' => 'Le cadre le plus impressionnant — or et violet mystique.',
                'price'       => 5000,
                'preview'     => '',
                'sort_order'  => 5,
                'data'        => [
                    'border' => '2px solid #ffd700',
                    'shadow' => '0 0 16px rgba(255,215,0,0.5), 0 0 32px rgba(163,130,255,0.3)',
                    'animation' => 'true',
                ],
            ],

            // ═══ SLEEVES ══════════════════════════════════════════════
            [
                'slug'        => 'sleeve-standard',
                'category'    => 'sleeve',
                'name'        => 'Sleeve Standard',
                'description' => 'Le dos de carte classique.',
                'price'       => 0,
                'preview'     => '',
                'sort_order'  => 1,
                'data'        => [
                    'background' => 'linear-gradient(135deg, #1a1d25, #2a2d35)',
                    'pattern'    => 'none',
                    'color'      => '#6B7280',
                ],
            ],
            [
                'slug'        => 'sleeve-pokeball',
                'category'    => 'sleeve',
                'name'        => 'Sleeve Pokéball',
                'description' => 'Un dos de carte aux couleurs de la Pokéball classique.',
                'price'       => 1000,
                'preview'     => '',
                'sort_order'  => 2,
                'data'        => [
                    'background' => 'linear-gradient(180deg, #ef4444 0%, #ef4444 45%, #333 45%, #333 55%, #fff 55%, #fff 100%)',
                    'color'      => '#ef4444',
                ],
            ],
            [
                'slug'        => 'sleeve-ultraball',
                'category'    => 'sleeve',
                'name'        => 'Sleeve Ultra Ball',
                'description' => 'Le design noir et jaune de la Ultra Ball.',
                'price'       => 2000,
                'preview'     => '',
                'sort_order'  => 3,
                'data'        => [
                    'background' => 'linear-gradient(135deg, #1a1a2e, #16213e, #0f3460)',
                    'accent'     => 'linear-gradient(135deg, #eab308, #f59e0b)',
                    'color'      => '#eab308',
                ],
            ],
            [
                'slug'        => 'sleeve-masterball',
                'category'    => 'sleeve',
                'name'        => 'Sleeve Master Ball',
                'description' => 'Le dos de carte légendaire — violet et élégant.',
                'price'       => 4000,
                'preview'     => '',
                'sort_order'  => 4,
                'data'        => [
                    'background' => 'linear-gradient(135deg, #581c87, #7c3aed, #a855f7)',
                    'accent'     => 'linear-gradient(135deg, #e879f9, #f0abfc)',
                    'color'      => '#a855f7',
                ],
            ],
            [
                'slug'        => 'sleeve-pikachu',
                'category'    => 'sleeve',
                'name'        => 'Sleeve Pikachu',
                'description' => 'Un design éclatant aux couleurs de Pikachu.',
                'price'       => 1500,
                'preview'     => '',
                'sort_order'  => 5,
                'data'        => [
                    'background' => 'linear-gradient(135deg, #fbbf24, #f59e0b, #eab308)',
                    'color'      => '#f59e0b',
                ],
            ],
        ];

        foreach ($items as $item) {
            ShopItem::updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
