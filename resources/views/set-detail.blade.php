<x-app-layout>
    <x-slot:title>{{ $set['name'] }} — PokeHub</x-slot:title>

    <div class="h-full flex" x-data="setDetail()" x-cloak>

        {{-- ═══ LEFT — Main content ═══════════════════════════════════ --}}
        <div class="flex-1 overflow-y-auto" id="main-scroll">

            {{-- ── Banner ─────────────────────────────────────────── --}}
            <div class="relative overflow-hidden" style="height: 280px; background: var(--bg-base);">
                <img src="{{ asset('images/banniere.jpg') }}" alt="Banner"
                     class="absolute inset-0 w-full h-full object-cover"
                     style="opacity: 0.35; filter: blur(1px);" />
                <div class="absolute inset-0"
                     style="background: linear-gradient(to top, var(--bg-base) 0%, rgba(10,12,16,0.5) 50%, rgba(10,12,16,0.2) 100%);"></div>

                {{-- Close --}}
                <a href="{{ route('encyclopedia') }}"
                   class="absolute top-5 right-5 w-9 h-9 rounded-xl flex items-center justify-center transition-all"
                   style="background: rgba(0,0,0,0.5); color: var(--text-muted); backdrop-filter: blur(8px);"
                   onmouseover="this.style.color='var(--text-primary)'; this.style.background='rgba(0,0,0,0.7)'"
                   onmouseout="this.style.color='var(--text-muted)'; this.style.background='rgba(0,0,0,0.5)'">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 384 512">
                        <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                    </svg>
                </a>

                {{-- Set info overlay --}}
                <div class="absolute bottom-0 left-0 right-0 px-10 pb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(8px);">
                            <img src="{{ $set['images']['symbol'] ?? '' }}" alt="" class="w-7 h-7 object-contain" onerror="this.style.display='none'" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-extrabold text-white leading-tight">{{ $set['name'] }}</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 flex-wrap">
                        @php
                            $infoPills = [
                                ['label' => 'Série', 'value' => $set['series']],
                                ['label' => 'Cartes', 'value' => $set['printedTotal']],
                                ['label' => 'Sortie', 'value' => \Carbon\Carbon::parse($set['releaseDate'])->translatedFormat('d F Y')],
                            ];
                        @endphp
                        @foreach($infoPills as $pill)
                            <span class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs"
                                  style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(4px);">
                                <span style="color: var(--text-muted);">{{ $pill['label'] }}</span>
                                <span class="font-semibold text-white">{{ $pill['value'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Action bar ─────────────────────────────────────── --}}
            <div class="px-10 py-4 flex items-center gap-3 flex-wrap" style="border-bottom: 1px solid var(--border);">
                <a href="{{ route('encyclopedia') }}" class="btn btn-surface btn-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96z"/></svg>
                    Encyclopédie
                </a>
                <a href="{{ route('open.booster', $set['id']) }}" class="btn btn-primary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 576 512"><path d="M290.8 48.6l78.4 29.7L288 109.5 206.8 78.3l78.4-29.7c1.8-.7 3.8-.7 5.7 0zM136 92.5V204.7c-1.3 .4-2.6 .8-3.9 1.3l-96 36.4C14.4 250.6 0 271.5 0 294.7V413.9c0 22.2 13.1 42.3 33.5 51.3l96 42.2c14.4 6.3 30.7 6.3 45.1 0L288 461.8l113.5 45.6c14.4 6.3 30.7 6.3 45.1 0l96-42.2c20.3-8.9 33.5-29.1 33.5-51.3V294.7c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-1.3-.5-2.6-.9-3.9-1.3V92.5c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-12.2-4.6-25.8-4.6-38 0l-96 36.4C150.4 48.4 136 69.3 136 92.5z"/></svg>
                    Ouvrir un booster
                </a>

                {{-- Collection progress --}}
                @auth
                    @php
                        $collectedCount = count($collectedCards);
                        $totalCards = count($cards);
                        $progressPct = $totalCards > 0 ? round(($collectedCount / $totalCards) * 100) : 0;
                    @endphp
                    <div class="ml-auto flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.1);">
                                <div class="h-full rounded-full transition-all" style="width: {{ $progressPct }}%; background: linear-gradient(90deg, var(--gold), #f59e0b);"></div>
                            </div>
                            <span class="text-xs font-bold" style="color: var(--gold);">{{ $collectedCount }}/{{ $totalCards }}</span>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-muted);">{{ $progressPct }}% collecté</span>
                    </div>
                @else
                    <span class="ml-auto text-xs font-medium" style="color: var(--text-muted);">{{ count($cards) }} cartes</span>
                @endauth
            </div>

            {{-- ── Cards grouped ───────────────────────────────────── --}}
            <div class="px-10 py-8 space-y-10">
                @foreach($grouped as $supertype => $group)
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <span class="section-pill">{{ count($group) }}</span>
                            <h2 class="text-lg font-bold text-white">
                                {{ $supertype === 'Pokémon' ? 'Pokémon' : ($supertype === 'Trainer' ? 'Trainers' : $supertype) }}
                            </h2>
                            <div class="flex-1 h-px" style="background: var(--border);"></div>
                        </div>

                        <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-7 lg:grid-cols-8 xl:grid-cols-10 gap-3">
                            @foreach($group as $index => $card)
                                @php
                                    $isCollected = isset($collectedCards[$card['id']]);
                                    $qty = $collectedCards[$card['id']] ?? 0;
                                @endphp
                                <div class="card-thumb anim-fade-up {{ !$isCollected && Auth::check() ? 'card-not-collected' : '' }}"
                                     style="animation-delay: {{ min($index * 15, 500) }}ms"
                                     @mouseenter="showPopup($event, @js($card))"
                                     @mouseleave="hidePopup()"
                                     @click="selectCard(@js($card))">
                                    <img src="{{ $card['images']['small'] ?? '' }}"
                                         alt="{{ $card['name'] }}"
                                         loading="lazy" />
                                    @if($isCollected)
                                        <span class="badge badge-gold badge-count">{{ $qty }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>

        {{-- ═══ RIGHT — Sidebar ═══════════════════════════════════════ --}}
        <div class="w-[340px] flex-shrink-0 h-full overflow-y-auto overflow-x-hidden"
             style="background: var(--bg-primary); border-left: 1px solid var(--border);">

            {{-- Header --}}
            <div class="px-6 py-5 flex items-center gap-3" style="border-bottom: 1px solid var(--border);">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 576 512">
                    <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329l104.2-103.1c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L388.2 150.3 316.9 18z"/>
                </svg>
                <span class="text-sm font-bold text-white">Carte List</span>
            </div>

            {{-- Set preview --}}
            <div class="px-5 py-5">
                <div class="set-card">
                    <div class="relative h-28 overflow-hidden">
                        <img src="{{ asset('images/banniere.jpg') }}" alt=""
                             class="w-full h-full object-cover" style="opacity: 0.5;" />
                        <div class="absolute inset-0"
                             style="background: linear-gradient(to top, var(--bg-elevated) 10%, transparent 100%);"></div>
                    </div>
                    <div class="px-4 pb-4 -mt-4 relative flex items-center justify-between">
                        <h3 class="font-bold text-white text-sm truncate">{{ $set['name'] }}</h3>
                        <a href="{{ route('set.show', $set['id']) }}"
                           class="text-[10px] font-bold px-2.5 py-1 rounded-md whitespace-nowrap ml-2 transition-colors"
                           style="color: white; background: var(--accent-bg);"
                           onmouseover="this.style.background='var(--accent-glow)'"
                           onmouseout="this.style.background='var(--gold-bg)'">
                            Voir détails
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card list --}}
            <div class="px-5 space-y-6 pb-6">
                @foreach($grouped as $supertype => $group)
                    <div>
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <span class="list-pill">{{ count($group) }}</span>
                            <span class="text-sm font-bold text-white">
                                {{ $supertype === 'Pokémon' ? 'Pokémon' : ($supertype === 'Trainer' ? 'Trainer' : $supertype) }}
                            </span>
                        </div>

                        <div class="space-y-px">
                            @php
                                $unique = [];
                                foreach ($group as $card) {
                                    $name = $card['name'];
                                    if (!isset($unique[$name])) {
                                        $unique[$name] = ['card' => $card, 'count' => 1, 'collected' => isset($collectedCards[$card['id']])];
                                    } else {
                                        $unique[$name]['count']++;
                                        if (isset($collectedCards[$card['id']])) {
                                            $unique[$name]['collected'] = true;
                                        }
                                    }
                                }
                            @endphp

                            @foreach($unique as $name => $entry)
                                <div class="flex items-center justify-between py-1.5 px-2.5 rounded-lg cursor-pointer group transition-colors"
                                     @click="selectCard(@js($entry['card']))"
                                     onmouseover="this.style.background='var(--bg-surface)'"
                                     onmouseout="this.style.background='transparent'">
                                    <span class="text-[13px] truncate transition-colors"
                                          style="color: {{ ($entry['collected'] || !Auth::check()) ? 'var(--text-secondary)' : 'var(--text-muted)' }}; {{ (!$entry['collected'] && Auth::check()) ? 'opacity: 0.4;' : '' }}">
                                        {{ $name }}
                                    </span>
                                    @if($entry['collected'] || !Auth::check())
                                        <span class="list-pill list-pill-sm flex-shrink-0 ml-2">{{ $entry['count'] }}</span>
                                    @else
                                        <svg class="w-3 h-3 flex-shrink-0 ml-2" style="color: var(--text-muted); opacity: 0.4;" fill="currentColor" viewBox="0 0 512 512">
                                            <path d="M256 0C114.6 0 0 114.6 0 256S114.6 512 256 512 512 397.4 512 256 397.4 0 256 0zm0 464C141.1 464 48 370.9 48 256S141.1 48 256 48s208 93.1 208 208-93.1 208-208 208zm-32-112h64v-48h-64v48zm0-80h64V144h-64v128z"/>
                                        </svg>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="px-5 py-4 flex items-center gap-3 text-[11px]" style="border-top: 1px solid var(--border); color: var(--text-muted);">
                <span>{{ $set['series'] }}</span>
                <span>·</span>
                <span>{{ $set['printedTotal'] }} cartes</span>
            </div>
        </div>

        {{-- ═══ Hover popup ═══════════════════════════════════════════ --}}
        <div x-show="popup.visible"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="card-popup"
             :style="{ top: popup.y + 'px', left: popup.x + 'px' }">
            <template x-if="popup.card">
                <div>
                    <div class="flex gap-4 mb-4">
                        <img :src="popup.card.images?.small" alt=""
                             class="w-24 h-[134px] rounded-xl object-cover flex-shrink-0"
                             style="box-shadow: var(--shadow-card);" />
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-white text-sm mb-3" x-text="popup.card.name"></p>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                                <div>
                                    <span class="label-xs block mb-0.5">Type</span>
                                    <span class="text-white font-medium" x-text="popup.card.supertype"></span>
                                </div>
                                <div>
                                    <span class="label-xs block mb-0.5">Forme</span>
                                    <span class="text-white font-medium" x-text="popup.card.subtypes?.join(', ') || '—'"></span>
                                </div>
                                <div>
                                    <span class="label-xs block mb-0.5">Élément</span>
                                    <div class="flex items-center gap-1">
                                        <template x-for="t in (popup.card.types || [])" :key="t">
                                            <span class="flex items-center gap-1 text-white font-medium">
                                                <span class="w-2.5 h-2.5 rounded-full" :style="{ background: typeColor(t) }"></span>
                                                <span x-text="t"></span>
                                            </span>
                                        </template>
                                        <span x-show="!popup.card.types?.length" style="color: var(--text-muted);">—</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="label-xs block mb-0.5">HP</span>
                                    <span class="text-white font-bold text-base" x-text="popup.card.hp || '—'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="separator mb-3"></div>

                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <span style="color: var(--text-muted);">Rareté </span>
                            <span class="font-semibold" style="color: white;" x-text="popup.card.rarity || '—'"></span>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">Artiste </span>
                            <span style="color: var(--text-secondary);" x-text="popup.card.artist || '—'"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function setDetail() {
            return {
                popup: { visible: false, card: null, x: 0, y: 0 },
                selectedCard: null,

                typeColor(type) {
                    const colors = {
                        Fire: '#EF4444', Water: '#3B82F6', Grass: '#22C55E',
                        Electric: '#EAB308', Psychic: '#D946EF', Fighting: '#F97316',
                        Darkness: '#64748B', Metal: '#94A3B8', Dragon: '#6366F1',
                        Fairy: '#EC4899', Colorless: '#9CA3AF', Lightning: '#EAB308',
                    };
                    return colors[type] || '#9CA3AF';
                },

                showPopup(event, card) {
                    const rect = event.target.closest('.card-thumb').getBoundingClientRect();
                    this.popup.card = card;
                    this.popup.x = rect.right + 16;
                    this.popup.y = Math.max(rect.top, 80);
                    this.popup.visible = true;

                    if (this.popup.x + 320 > window.innerWidth - 360) {
                        this.popup.x = rect.left - 336;
                    }
                    if (this.popup.y + 240 > window.innerHeight) {
                        this.popup.y = window.innerHeight - 260;
                    }
                },

                hidePopup() {
                    this.popup.visible = false;
                },

                selectCard(card) {
                    this.selectedCard = this.selectedCard?.id === card.id ? null : card;
                },
            };
        }
    </script>
</x-app-layout>
