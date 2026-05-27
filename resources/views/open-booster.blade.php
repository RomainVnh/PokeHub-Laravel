<x-app-layout>
    <x-slot:title>Booster {{ $set['name'] }} — PokeHub</x-slot:title>

    @php
        // Flexible rarity tier classification (works with any API's rarity names)
        $getTier = function(?string $rarity): ?string {
            if (!$rarity) return null;
            $lower = strtolower($rarity);
            if (str_contains($lower, 'secret') || str_contains($lower, 'rainbow') || str_contains($lower, 'hyper') || (str_contains($lower, 'shiny') && str_contains($lower, 'ultra'))) return 'secret';
            if (str_contains($lower, 'illustration') || str_contains($lower, 'amazing')) return 'illustration';
            if (str_contains($lower, 'ultra') || str_contains($lower, 'double') || str_contains($lower, 'ace') || str_contains($lower, ' ex') || str_contains($lower, ' gx') || str_contains($lower, 'vmax') || str_contains($lower, 'vstar')) return 'ultra';
            if (str_contains($lower, 'rare') || str_contains($lower, 'holo')) return 'rare';
            return null;
        };

        // Drop rate per tier (from the booster algorithm)
        $dropRates = [
            'ultra'        => '4.5%',
            'illustration' => '1.2%',
            'secret'       => '0.3%',
        ];

        $cardsData = [];
        $rareCount = 0;
        foreach ($drawn as $i => $card) {
            $tier = $getTier($card['rarity'] ?? null);
            if ($tier) $rareCount++;
            $cardsData[] = [
                'id'         => $card['id'],
                'isNew'      => $newCards[$card['id']] ?? true,
                'rarityTier' => $tier,
                'dropRate'   => $tier ? ($dropRates[$tier] ?? null) : null,
            ];
        }
    @endphp

    <div class="min-h-full flex flex-col items-center justify-center px-8 py-16"
         x-data="{
            revealed: [false, false, false, false, false],
            cards: {{ json_encode($cardsData) }},
            rareCount: {{ $rareCount }},
            isAuth: {{ Auth::check() ? 'true' : 'false' }},
            zoomedCard: null,
            get allRevealed() { return this.revealed.every(Boolean); },
            showZoom(i) {
                if (!this.revealed[i]) return;
                this.zoomedCard = i;
            },
            closeZoom() { this.zoomedCard = null; },
            isCardNew(i) {
                const card = this.cards[i];
                if (this.isAuth) return card.isNew;
                const seen = JSON.parse(localStorage.getItem('pokehub_seen') || '[]');
                return !seen.includes(card.id);
            },
            markSeen(i) {
                if (!this.isAuth) {
                    const card = this.cards[i];
                    const seen = JSON.parse(localStorage.getItem('pokehub_seen') || '[]');
                    if (!seen.includes(card.id)) {
                        seen.push(card.id);
                        localStorage.setItem('pokehub_seen', JSON.stringify(seen));
                    }
                }
            },
            reveal(i) {
                if (this.revealed[i]) return;
                this.revealed[i] = true;
                document.getElementById('flipper-' + i).style.transform = 'rotateY(0deg)';
                const isNew = this.isCardNew(i);
                const tier = this.cards[i].rarityTier;
                this.markSeen(i);
                setTimeout(() => {
                    if (isNew) {
                        const badge = document.getElementById('new-badge-' + i);
                        if (badge) { badge.style.display = 'block'; badge.classList.add('show'); }
                    }
                    if (tier) {
                        const glow = document.getElementById('rarity-glow-' + i);
                        if (glow) glow.classList.add('show');
                    }
                }, 500);
            },
            revealAll() {
                for (let i = 0; i < 5; i++) {
                    if (this.revealed[i]) continue;
                    const isNew = this.isCardNew(i);
                    const tier = this.cards[i].rarityTier;
                    this.revealed[i] = true;
                    document.getElementById('flipper-' + i).style.transform = 'rotateY(0deg)';
                    this.markSeen(i);
                    setTimeout((idx, n, t) => {
                        if (n) {
                            const badge = document.getElementById('new-badge-' + idx);
                            if (badge) { badge.style.display = 'block'; badge.classList.add('show'); }
                        }
                        if (t) {
                            const glow = document.getElementById('rarity-glow-' + idx);
                            if (glow) glow.classList.add('show');
                        }
                    }, 500 + i * 100, i, isNew, tier);
                }
            }
         }">

        {{-- Multi-rare banner --}}
        @if($rareCount >= 2)
            <div class="multi-rare-banner show tier-{{ min($rareCount, 5) }} mb-8 anim-fade-up">
                @if($rareCount >= 5)
                    ✦ GOD PACK ✦ — {{ $rareCount }} cartes rares !
                @elseif($rareCount >= 4)
                    ★ BOOSTER LÉGENDAIRE ★ — {{ $rareCount }} cartes rares !
                @elseif($rareCount >= 3)
                    ★ BOOSTER ÉPIQUE ★ — {{ $rareCount }} cartes rares !
                @else
                    ★ BOOSTER CHANCEUX ★ — {{ $rareCount }} cartes rares !
                @endif
            </div>
        @endif

        {{-- Set info --}}
        <div class="text-center mb-12 anim-fade-up">
            <img src="{{ $set['images']['logo'] ?? '' }}" alt="{{ $set['name'] }}"
                 class="h-20 mx-auto mb-5 object-contain" />
            <h1 class="text-2xl font-extrabold mb-2" style="color: var(--text-primary);">{{ $set['name'] }}</h1>
            <p class="text-sm" style="color: var(--text-muted);">Booster de 5 cartes</p>
        </div>

        {{-- Cards --}}
        <div class="flex items-center gap-5 mb-12">
            @foreach($drawn as $i => $card)
                @php
                    $tier = $getTier($card['rarity'] ?? null);
                @endphp
                <div class="relative cursor-pointer anim-fade-up"
                     @click="revealed[{{ $i }}] ? showZoom({{ $i }}) : reveal({{ $i }})"
                     style="perspective: 1200px; width: 200px; animation-delay: {{ $i * 100 }}ms;">

                    {{-- NEW badge --}}
                    <div id="new-badge-{{ $i }}" class="new-badge" style="display: none;">
                        NEW
                    </div>

                    {{-- Rarity glow (per-tier) --}}
                    @if($tier)
                        <div id="rarity-glow-{{ $i }}" class="rarity-glow rarity-{{ $tier }}"></div>
                    @endif

                    <div id="flipper-{{ $i }}"
                         style="position: relative; width: 100%; transform-style: preserve-3d; aspect-ratio: 63/88; transition: transform 0.7s ease-out; transform: rotateY(180deg);">

                        {{-- Front (card image) --}}
                        <div style="position: absolute; inset: 0; border-radius: 12px; overflow: hidden; backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                            <img src="{{ $card['images']['large'] ?? $card['images']['small'] }}"
                                 alt="{{ $card['name'] }}"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                            @if($card['rarity'] ?? false)
                                <span style="position: absolute; bottom: 10px; left: 10px; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; background: rgba(0,0,0,0.7); color: white;">
                                    {{ $card['rarity'] }}
                                </span>
                            @endif
                            @php $rate = $dropRates[$tier] ?? null; @endphp
                            @if($rate)
                                <span style="position: absolute; bottom: 10px; right: 10px; font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 6px; color: #fbbf24; background: rgba(0,0,0,0.75); border: 1px solid rgba(251,191,36,0.3); letter-spacing: 0.5px;">
                                    {{ $rate }} drop
                                </span>
                            @endif
                        </div>

                        {{-- Back (card back) --}}
                        <div style="position: absolute; inset: 0; border-radius: 12px; overflow: hidden; backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                            <img src="{{ asset('images/card-back.png') }}" alt="Card back"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex gap-4 anim-fade-up" style="animation-delay: 600ms;">
            <button @click="revealAll()" class="btn btn-surface" x-show="!allRevealed">
                Tout révéler
            </button>
            <a href="{{ route('open.booster', $set['id']) }}" class="btn btn-primary">
                Nouveau booster
            </a>
            <a href="{{ route('set.show', $set['id']) }}" class="btn btn-ghost">
                Voir l'édition
            </a>
        </div>

        {{-- ── Zoomed card overlay ──────────────────────────────── --}}
        <div x-show="zoomedCard !== null" x-transition.opacity
             @click.self="closeZoom()" @keydown.escape.window="closeZoom()"
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background: rgba(0,0,0,0.85); backdrop-filter: blur(12px);">
            <template x-if="zoomedCard !== null">
                <div class="relative" style="max-width: 380px; width: 90vw;">
                    @foreach($drawn as $i => $card)
                        <div x-show="zoomedCard === {{ $i }}" x-transition.scale class="relative">
                            <img src="{{ $card['images']['large'] ?? $card['images']['small'] }}"
                                 alt="{{ $card['name'] }}"
                                 class="w-full rounded-2xl shadow-2xl" />
                            <div class="text-center mt-4">
                                <p class="text-lg font-bold" style="color: var(--text-primary);">{{ $card['name'] }}</p>
                                @if($card['rarity'] ?? false)
                                    <p class="text-sm mt-1" style="color: var(--gold);">{{ $card['rarity'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <button @click="closeZoom()" class="absolute -top-3 -right-3 w-8 h-8 rounded-full flex items-center justify-center"
                            style="background: rgba(255,255,255,0.15); color: white; backdrop-filter: blur(8px);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>
