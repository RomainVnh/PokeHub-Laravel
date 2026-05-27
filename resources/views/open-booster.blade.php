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

        $cardsData = [];
        $rareCount = 0;
        foreach ($drawn as $i => $card) {
            $tier = $getTier($card['rarity'] ?? null);
            if ($tier) $rareCount++;
            $cardsData[] = [
                'id'        => $card['id'],
                'isNew'     => $newCards[$card['id']] ?? true,
                'rarityTier' => $tier,
            ];
        }
    @endphp

    <div class="min-h-full flex flex-col items-center justify-center px-8 py-16"
         x-data="{
            revealed: [false, false, false, false, false],
            cards: {{ json_encode($cardsData) }},
            rareCount: {{ $rareCount }},
            isAuth: {{ Auth::check() ? 'true' : 'false' }},
            get allRevealed() { return this.revealed.every(Boolean); },
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
                     @click="reveal({{ $i }})"
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
    </div>
</x-app-layout>
