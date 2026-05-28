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

        $dropRates = ($isPremium ?? false) ? [
            'ultra'        => '28.8%',
            'illustration' => '14.4%',
            'secret'       => '4.8%',
        ] : [
            'ultra'        => '13.78%',
            'illustration' => '1.14%',
            'secret'       => '0.29%',
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

    <div class="min-h-full flex flex-col items-center justify-center px-4 sm:px-8 py-8 sm:py-16"
         x-data="{
            revealed: [false, false, false, false, false],
            cards: {{ json_encode($cardsData) }},
            rareCount: {{ $rareCount }},
            isAuth: {{ Auth::check() ? 'true' : 'false' }},
            zoomedCard: null,
            mobileIdx: 0,
            mobileSummary: false,
            premiumConfirm: false,
            get allRevealed() { return this.revealed.every(Boolean); },
            get isMobile() { return window.innerWidth <= 768; },
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
                const flipper = document.getElementById('flipper-' + i) || document.getElementById('m-flipper-' + i);
                if (flipper) flipper.style.transform = 'rotateY(0deg)';
                const isNew = this.isCardNew(i);
                const tier = this.cards[i].rarityTier;
                this.markSeen(i);
                setTimeout(() => {
                    if (isNew) {
                        const badge = document.getElementById('new-badge-' + i) || document.getElementById('m-new-badge-' + i);
                        if (badge) { badge.style.display = 'block'; badge.classList.add('show'); }
                    }
                    if (tier) {
                        const glow = document.getElementById('rarity-glow-' + i) || document.getElementById('m-rarity-glow-' + i);
                        if (glow) glow.classList.add('show');
                    }
                }, 500);
            },
            mobileRevealAndNext() {
                const i = this.mobileIdx;
                if (!this.revealed[i]) {
                    this.reveal(i);
                } else if (i < 4) {
                    this.mobileIdx++;
                } else {
                    this.mobileSummary = true;
                }
            },
            revealAll() {
                for (let i = 0; i < 5; i++) {
                    if (this.revealed[i]) continue;
                    const isNew = this.isCardNew(i);
                    const tier = this.cards[i].rarityTier;
                    this.revealed[i] = true;
                    const flipper = document.getElementById('flipper-' + i);
                    if (flipper) flipper.style.transform = 'rotateY(0deg)';
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

        {{-- Premium badge --}}
        @if($isPremium ?? false)
            <div class="mb-6 anim-fade-up">
                <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-bold"
                      style="background: linear-gradient(135deg, rgba(163,130,255,0.2), rgba(212,168,67,0.2)); border: 1px solid rgba(163,130,255,0.4); color: #a382ff;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3L288.1 439.8 416.2 508.3c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329 542.4 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L380.9 150.3 316.9 18z"/></svg>
                    BOOSTER PREMIUM
                </span>
            </div>
        @endif

        {{-- Set info --}}
        <div class="text-center mb-12 anim-fade-up">
            <img src="{{ $set['images']['logo'] ?? '' }}" alt="{{ $set['name'] }}"
                 class="h-20 mx-auto mb-5 object-contain" />
            <h1 class="text-2xl font-extrabold mb-2" style="color: var(--text-primary);">{{ $set['name'] }}</h1>
            <p class="text-sm" style="color: var(--text-muted);">
                {{ ($isPremium ?? false) ? 'Booster Premium — 1 ultra rare garantie' : 'Booster de 5 cartes' }}
            </p>
        </div>

        {{-- ═══ DESKTOP: row of 5 cards ═══════════════════════════════ --}}
        <div class="booster-cards-desktop flex items-center gap-5 mb-12">
            @foreach($drawn as $i => $card)
                @php $tier = $getTier($card['rarity'] ?? null); @endphp
                <div class="relative cursor-pointer anim-fade-up"
                     @click="revealed[{{ $i }}] ? showZoom({{ $i }}) : reveal({{ $i }})"
                     style="perspective: 1200px; width: 200px; animation-delay: {{ $i * 100 }}ms;">
                    <div id="new-badge-{{ $i }}" class="new-badge" style="display: none;">NEW</div>
                    @if($tier)
                        <div id="rarity-glow-{{ $i }}" class="rarity-glow rarity-{{ $tier }}"></div>
                    @endif
                    <div id="flipper-{{ $i }}"
                         style="position: relative; width: 100%; transform-style: preserve-3d; aspect-ratio: 63/88; transition: transform 0.7s ease-out; transform: rotateY(180deg);">
                        <div style="position: absolute; inset: 0; border-radius: 12px; overflow: hidden; backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                            <img src="{{ $card['images']['large'] ?? $card['images']['small'] }}" alt="{{ $card['name'] }}"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                            @if($card['rarity'] ?? false)
                                <span style="position: absolute; bottom: 10px; left: 10px; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; background: rgba(0,0,0,0.7); color: white;">{{ $card['rarity'] }}</span>
                            @endif
                            @php $rate = $dropRates[$tier] ?? null; @endphp
                            @if($rate)
                                <span style="position: absolute; bottom: 10px; right: 10px; font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 6px; color: #fbbf24; background: rgba(0,0,0,0.75); border: 1px solid rgba(251,191,36,0.3); letter-spacing: 0.5px;">{{ $rate }} drop</span>
                            @endif
                        </div>
                        <div style="position: absolute; inset: 0; border-radius: 12px; overflow: hidden; backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                            <img src="{{ asset('images/card-back.png') }}" alt="Card back"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Desktop actions --}}
        <div class="booster-cards-desktop flex gap-4 anim-fade-up flex-wrap justify-center" style="animation-delay: 600ms;">
            <button @click="revealAll()" class="btn btn-surface" x-show="!allRevealed">Tout révéler</button>
            <a href="{{ route('open.booster', $set['id']) }}" class="btn btn-primary">Nouveau booster</a>
            @auth
                @php
                    $user = auth()->user();
                    $canFreeOpen = !$user->last_premium_booster_at || !$user->last_premium_booster_at->isToday();
                @endphp
                <button @click="premiumConfirm = true" class="btn"
                        style="background: linear-gradient(135deg, #a382ff, #d4a843); color: white; font-weight: 700;">★ Premium</button>
            @endauth
            <a href="{{ route('set.show', $set['id']) }}" class="btn btn-ghost">Voir l'édition</a>
        </div>

        {{-- ═══ MOBILE: card-by-card then summary ═════════════════════ --}}
        <div class="booster-cards-mobile" x-show="!mobileSummary">
            {{-- Progress dots --}}
            <div class="flex justify-center gap-2 mb-6">
                @for($i = 0; $i < 5; $i++)
                    <button @click="mobileIdx = {{ $i }}"
                            class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                            :style="mobileIdx === {{ $i }}
                                ? 'background: var(--accent); transform: scale(1.3)'
                                : (revealed[{{ $i }}] ? 'background: var(--text-muted)' : 'background: var(--border)')">
                    </button>
                @endfor
            </div>

            {{-- Counter --}}
            <p class="text-center text-sm font-bold mb-4" style="color: var(--text-secondary);">
                <span x-text="mobileIdx + 1">1</span> / 5
            </p>

            {{-- Single card view --}}
            <div class="flex justify-center mb-6">
                @foreach($drawn as $i => $card)
                    @php $tier = $getTier($card['rarity'] ?? null); @endphp
                    <div x-show="mobileIdx === {{ $i }}" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                         class="relative cursor-pointer"
                         @click="mobileRevealAndNext()"
                         style="perspective: 1200px; width: 220px;">
                        <div id="m-new-badge-{{ $i }}" class="new-badge" style="display: none;">NEW</div>
                        @if($tier)
                            <div id="m-rarity-glow-{{ $i }}" class="rarity-glow rarity-{{ $tier }}"></div>
                        @endif
                        <div id="m-flipper-{{ $i }}"
                             style="position: relative; width: 100%; transform-style: preserve-3d; aspect-ratio: 63/88; transition: transform 0.7s ease-out; transform: rotateY(180deg);">
                            <div style="position: absolute; inset: 0; border-radius: 12px; overflow: hidden; backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                                <img src="{{ $card['images']['large'] ?? $card['images']['small'] }}" alt="{{ $card['name'] }}"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                                @if($card['rarity'] ?? false)
                                    <span style="position: absolute; bottom: 10px; left: 10px; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; background: rgba(0,0,0,0.7); color: white;">{{ $card['rarity'] }}</span>
                                @endif
                                @php $rate = $dropRates[$tier] ?? null; @endphp
                                @if($rate)
                                    <span style="position: absolute; bottom: 10px; right: 10px; font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 6px; color: #fbbf24; background: rgba(0,0,0,0.75); border: 1px solid rgba(251,191,36,0.3); letter-spacing: 0.5px;">{{ $rate }} drop</span>
                                @endif
                            </div>
                            <div style="position: absolute; inset: 0; border-radius: 12px; overflow: hidden; backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                                <img src="{{ asset('images/card-back.png') }}" alt="Card back"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Mobile tap hint --}}
            <p class="text-center text-xs mb-4" style="color: var(--text-muted);"
               x-show="!revealed[mobileIdx]">Toucher pour révéler</p>
            <p class="text-center text-xs mb-4" style="color: var(--text-muted);"
               x-show="revealed[mobileIdx] && mobileIdx < 4">Toucher pour la carte suivante</p>
            <p class="text-center text-xs mb-4" style="color: var(--text-muted);"
               x-show="revealed[mobileIdx] && mobileIdx === 4 && !mobileSummary">Toucher pour le bilan</p>
        </div>

        {{-- ═══ MOBILE: Summary (bilan) ═══════════════════════════════ --}}
        <div class="booster-cards-mobile" x-show="mobileSummary" x-transition.opacity>
            <h2 class="text-lg font-extrabold text-center mb-6" style="color: var(--text-primary);">Bilan du booster</h2>
            <div class="grid grid-cols-3 gap-3 mb-8 px-2" style="max-width: 340px; margin: 0 auto;">
                @foreach($drawn as $i => $card)
                    @php $tier = $getTier($card['rarity'] ?? null); @endphp
                    <div class="relative cursor-pointer rounded-xl overflow-hidden {{ $tier ? 'card-tier-'.$tier : '' }}"
                         @click="showZoom({{ $i }})"
                         style="aspect-ratio: 63/88; border: 1px solid var(--border);">
                        <img src="{{ $card['images']['large'] ?? $card['images']['small'] }}" alt="{{ $card['name'] }}"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                        @if($card['rarity'] ?? false)
                            <span style="position: absolute; bottom: 4px; left: 4px; font-size: 7px; font-weight: 700; padding: 1px 5px; border-radius: 4px; background: rgba(0,0,0,0.7); color: white;">{{ $card['rarity'] }}</span>
                        @endif
                        <template x-if="isCardNew({{ $i }})">
                            <span style="position: absolute; top: 4px; right: 4px; font-size: 7px; font-weight: 800; padding: 1px 5px; border-radius: 4px; background: linear-gradient(135deg, #22c55e, #16a34a); color: white;">NEW</span>
                        </template>
                    </div>
                @endforeach
            </div>

            {{-- Mobile action buttons --}}
            <div class="flex flex-col gap-3 px-4" style="max-width: 340px; margin: 0 auto;">
                <a href="{{ route('open.booster', $set['id']) }}" class="btn btn-primary w-full justify-center">Nouveau booster</a>
                @auth
                    <button @click="premiumConfirm = true" class="btn w-full justify-center"
                            style="background: linear-gradient(135deg, #a382ff, #d4a843); color: white; font-weight: 700;">★ Premium</button>
                @endauth
                <a href="{{ route('set.show', $set['id']) }}" class="btn btn-ghost w-full justify-center">Voir l'édition</a>
            </div>
        </div>

        {{-- ── Premium confirmation popup ────────────────────────── --}}
        @auth
            <div x-show="premiumConfirm" x-transition.opacity
                 @click.self="premiumConfirm = false" @keydown.escape.window="premiumConfirm = false"
                 class="fixed inset-0 z-50 flex items-center justify-center"
                 style="background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);">
                <div x-show="premiumConfirm" x-transition.scale.origin.center
                     class="relative w-full max-w-sm mx-4 rounded-2xl overflow-hidden"
                     style="background: var(--bg-card); border: 1px solid rgba(163,130,255,0.3);">
                    <div class="h-1.5" style="background: linear-gradient(90deg, #a382ff, #d4a843);"></div>
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                             style="background: linear-gradient(135deg, #a382ff, #d4a843); box-shadow: 0 8px 24px rgba(163,130,255,0.3);">
                            <svg class="w-7 h-7" fill="white" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3L288.1 439.8 416.2 508.3c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329 542.4 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L380.9 150.3 316.9 18z"/></svg>
                        </div>
                        <h3 class="text-lg font-extrabold mb-1" style="color: var(--text-primary);">Booster Premium</h3>
                        <p class="text-sm mb-4" style="color: var(--text-secondary);">{{ $set['name'] }}</p>

                        <div class="p-4 rounded-xl mb-4" style="background: var(--bg-surface); border: 1px solid var(--border);">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium" style="color: var(--text-muted);">Coût</span>
                                @if($canFreeOpen)
                                    <span class="text-sm font-bold" style="color: #22c55e;">Gratuit (1/jour)</span>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" style="color: #d4a843;" fill="currentColor" viewBox="0 0 512 512"><path d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .3-24.5 .8C124.3 109.2 96.4 88 96.4 64c0-35.3 57.3-64 128-64s128 28.7 128 64c0 5.3-1 10.4-2.8 15.3C430.1 82.6 512 78 512 80z"/></svg>
                                        <span class="text-sm font-bold" style="color: #d4a843;">500 PokéTokens</span>
                                    </div>
                                @endif
                            </div>
                            @if(!$canFreeOpen)
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium" style="color: var(--text-muted);">Ton solde</span>
                                    <span class="text-sm font-bold" style="color: var(--text-primary);">{{ number_format(auth()->user()->poketokens, 0, ',', ' ') }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2 pt-2" style="border-top: 1px solid var(--border);">
                                    <span class="text-xs font-medium" style="color: var(--text-muted);">Après achat</span>
                                    <span class="text-sm font-bold" style="color: {{ auth()->user()->poketokens >= 500 ? '#22c55e' : '#f87171' }};">{{ number_format(max(0, auth()->user()->poketokens - 500), 0, ',', ' ') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2 text-left mb-5">
                            <div class="flex items-center gap-2.5 text-xs" style="color: var(--text-secondary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #a382ff;" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                1 carte Ultra Rare, EX ou Full Art garantie
                            </div>
                            <div class="flex items-center gap-2.5 text-xs" style="color: var(--text-secondary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #a382ff;" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                Taux 4.8% secret, 14.4% illustration, 28.8% ultra
                            </div>
                        </div>

                        @if($canFreeOpen)
                            <div class="flex gap-3">
                                <button @click="premiumConfirm = false" class="btn btn-surface flex-1">Annuler</button>
                                <a href="{{ route('open.booster.premium', $set['id']) }}" class="btn flex-1 text-center"
                                   style="background: linear-gradient(135deg, #a382ff, #d4a843); color: white; font-weight: 700;">
                                    Ouvrir
                                </a>
                            </div>
                        @elseif(auth()->user()->poketokens >= 500)
                            <div class="flex gap-3">
                                <button @click="premiumConfirm = false" class="btn btn-surface flex-1">Annuler</button>
                                <form action="{{ route('open.booster.premium', $set['id']) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="btn w-full"
                                            style="background: linear-gradient(135deg, #a382ff, #d4a843); color: white; font-weight: 700;">
                                        Payer & Ouvrir
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-xs mb-4" style="color: #f87171;">Solde insuffisant pour ouvrir un booster premium.</p>
                            <button @click="premiumConfirm = false" class="btn btn-surface w-full">Fermer</button>
                        @endif
                    </div>
                    <button @click="premiumConfirm = false"
                            class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center"
                            style="background: rgba(255,255,255,0.08); color: var(--text-muted);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </button>
                </div>
            </div>
        @endauth

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
