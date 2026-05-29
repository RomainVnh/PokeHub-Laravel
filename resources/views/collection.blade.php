<x-app-layout>
    <x-slot:title>Ma Collection — PokeHub</x-slot:title>

    <div class="min-h-full" x-data="{
        search: '',
        filterSet: 'all',
        filterRarity: 'all',
        filterFavorites: false,
        sortBy: 'recent',
        sellModal: false,
        sellCard: null,
        sellQty: 1,
        sellMaxQty: 0,
        sellPrice: 0,
        favorites: @json($favorites ?? []),
        openSell(card) {
            this.sellCard = card;
            this.sellMaxQty = card.quantity - 1;
            this.sellQty = 1;
            this.sellPrice = card.sellPrice;
            this.sellModal = true;
        },
        isFavorite(cardId) {
            return this.favorites.includes(cardId);
        },
        async toggleFavorite(card, event) {
            event.stopPropagation();
            try {
                const res = await fetch('{{ route('collection.favorite') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        card_id: card.card_id,
                        card_name: card.card_name,
                        set_id: card.set_id,
                        image_url: card.image_url || '',
                        rarity: card.rarity || null,
                    }),
                });
                const data = await res.json();
                if (data.favorited) {
                    this.favorites.push(card.card_id);
                } else {
                    this.favorites = this.favorites.filter(id => id !== card.card_id);
                }
            } catch (e) { console.error(e); }
        }
    }">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <div class="page-hero px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <img src="{{ asset('images/bannieredarkrai.webp') }}" alt="" class="page-hero-bg" />
            <div class="page-hero-overlay"></div>
            <div class="relative z-10">
                <span class="label-xs block mb-3" style="color: var(--text-primary);">Ma Collection</span>
                <h1 class="text-3xl font-black mb-2 tracking-tight" style="color: var(--text-primary);">Collection</h1>
                <p class="text-sm mb-6" style="color: var(--text-muted);">
                    {{ $totalUnique }} cartes uniques · {{ $totalCards }} cartes au total
                </p>

                {{-- Stats pills --}}
                <div class="flex gap-3 flex-wrap mb-6">
                    @foreach(array_slice($rarityStats, 0, 5) as $rarity => $count)
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold"
                              style="background: rgba(255,255,255,0.08); color: var(--text-primary); border: 1px solid rgba(255,255,255,0.1);">
                            {{ $rarity }} · {{ $count }}
                        </span>
                    @endforeach
                </div>

                {{-- Filters --}}
                <div class="flex gap-3 flex-wrap max-w-3xl">
                    <div class="relative flex-1 min-w-[200px]">
                        <svg class="absolute w-4 h-4 pointer-events-none" style="left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" fill="currentColor" viewBox="0 0 512 512">
                            <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                        </svg>
                        <input x-model="search" type="text" placeholder="Rechercher une carte..."
                               class="input-dark" style="padding-left: 40px;" />
                    </div>
                    <select x-model="filterSet" class="input-dark w-auto min-w-[180px] cursor-pointer">
                        <option value="all">Toutes les éditions</option>
                        @foreach($collection as $col)
                            <option value="{{ $col['set']['id'] }}">{{ $col['set']['name'] }}</option>
                        @endforeach
                    </select>
                    <select x-model="filterRarity" class="input-dark w-auto min-w-[150px] cursor-pointer">
                        <option value="all">Toutes raretés</option>
                        @foreach(array_keys($rarityStats) as $rarity)
                            <option value="{{ $rarity }}">{{ $rarity }}</option>
                        @endforeach
                    </select>
                    <select x-model="sortBy" class="input-dark w-auto min-w-[150px] cursor-pointer">
                        <option value="recent">Plus récentes</option>
                        <option value="name">Nom A-Z</option>
                        <option value="rarity">Par rareté</option>
                        <option value="quantity">Par quantité</option>
                    </select>
                    <button @click="filterFavorites = !filterFavorites"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold transition-all cursor-pointer"
                            :style="filterFavorites
                                ? 'background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);'
                                : 'background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid var(--border);'">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>
                        Favoris
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Cards grid ──────────────────────────────────────────── --}}
        <div class="px-10 py-10 space-y-10">
            @if(count($collection) === 0)
                <div class="text-center py-20">
                    <svg class="w-16 h-16 mx-auto mb-6" style="color: var(--text-muted);" fill="currentColor" viewBox="0 0 576 512">
                        <path d="M290.8 48.6l78.4 29.7L288 109.5 206.8 78.3l78.4-29.7c1.8-.7 3.8-.7 5.7 0zM136 92.5V204.7c-1.3 .4-2.6 .8-3.9 1.3l-96 36.4C14.4 250.6 0 271.5 0 294.7V413.9c0 22.2 13.1 42.3 33.5 51.3l96 42.2c14.4 6.3 30.7 6.3 45.1 0L288 461.8l113.5 45.6c14.4 6.3 30.7 6.3 45.1 0l96-42.2c20.3-8.9 33.5-29.1 33.5-51.3V294.7c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-1.3-.5-2.6-.9-3.9-1.3V92.5c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-12.2-4.6-25.8-4.6-38 0l-96 36.4C150.4 48.4 136 69.3 136 92.5z"/>
                    </svg>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--text-primary);">Aucune carte dans ta collection</h3>
                    <p class="text-sm mb-6" style="color: var(--text-muted);">Ouvre des boosters pour commencer ta collection !</p>
                    <a href="{{ route('open.index') }}" class="btn btn-primary">Ouvrir un booster</a>
                </div>
            @else
                @foreach($collection as $col)
                    <div x-show="filterSet === 'all' || filterSet === '{{ $col['set']['id'] }}'" x-cloak>
                        <div class="flex items-center gap-3 mb-5">
                            @if($col['set']['images']['logo'] ?? false)
                                <img src="{{ $col['set']['images']['logo'] }}" alt="" class="h-8 object-contain" />
                            @endif
                            <h2 class="text-base font-bold" style="color: var(--text-primary);">{{ $col['set']['name'] }}</h2>
                            <span class="list-pill">{{ $col['count'] }}/{{ $col['set']['printedTotal'] }}</span>
                            <div class="flex-1 h-px" style="background: var(--border);"></div>
                            <a href="{{ route('set.show', $col['set']['id']) }}" class="text-xs font-semibold" style="color: var(--gold);">Voir l'édition</a>
                        </div>

                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-3">
                            @foreach($col['cards'] as $card)
                                @php
                                    $imgUrl = $card['image_url'] ?? '';
                                    if (!$imgUrl) {
                                        // Fallback: try to build URL from card_id (won't work for all sets)
                                        $parts = explode('-', $card['card_id']);
                                        $imgUrl = 'https://assets.tcgdex.net/en/' . $card['set_id'] . '/' . ($parts[1] ?? $card['card_id']) . '/low.webp';
                                    }
                                @endphp
                                <div x-show="(filterRarity === 'all' || '{{ addslashes($card['rarity'] ?? '') }}' === filterRarity) && (!search || '{{ strtolower(addslashes($card['card_name'])) }}'.includes(search.toLowerCase())) && (!filterFavorites || isFavorite('{{ addslashes($card['card_id']) }}'))"
                                     class="relative group {{ $card['quantity'] > 1 ? 'cursor-pointer' : '' }}"
                                     @if($card['quantity'] > 1) @click="openSell(@js($card))" @endif>
                                    <div class="card-item rounded-lg overflow-hidden border transition-all duration-200"
                                         style="border-color: var(--border); background: var(--bg-card);">
                                        <img src="{{ $imgUrl }}"
                                             alt="{{ $card['card_name'] }}"
                                             class="w-full aspect-[63/88] object-cover"
                                             loading="lazy"
                                             onerror="this.style.opacity='0.3'" />
                                    </div>
                                    {{-- Favorite heart --}}
                                    <button @click="toggleFavorite(@js($card), $event)"
                                            class="absolute top-1 left-1 w-6 h-6 rounded-full flex items-center justify-center transition-all opacity-0 group-hover:opacity-100"
                                            :class="isFavorite('{{ addslashes($card['card_id']) }}') ? 'opacity-100' : ''"
                                            :style="isFavorite('{{ addslashes($card['card_id']) }}')
                                                ? 'background: rgba(239,68,68,0.85); color: white;'
                                                : 'background: rgba(0,0,0,0.6); color: rgba(255,255,255,0.7);'">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 512 512"><path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>
                                    </button>
                                    @if($card['quantity'] > 1)
                                        <span class="absolute -top-1 -right-1 text-[10px] font-black px-1.5 py-0.5 rounded-full"
                                              style="background: var(--gold); color: #000;">
                                            x{{ $card['quantity'] }}
                                        </span>
                                    @endif
                                    <p class="text-[10px] font-medium mt-1 truncate text-center" style="color: var(--text-muted);">{{ $card['card_name'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- ── Sell card popup ─────────────────────────────────────── --}}
        <div x-show="sellModal" x-transition.opacity x-cloak
             @click.self="sellModal = false" @keydown.escape.window="sellModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);">
            <div x-show="sellModal" x-transition.scale.origin.center
                 class="relative w-full max-w-sm mx-4 rounded-2xl overflow-hidden"
                 style="background: var(--bg-card); border: 1px solid rgba(212,168,67,0.3);">
                <div class="h-1.5" style="background: linear-gradient(90deg, #d4a843, #f59e0b);"></div>
                <div class="p-6">
                    <template x-if="sellCard">
                        <div>
                            <div class="flex gap-4 mb-5">
                                <img :src="sellCard.image_url" alt=""
                                     class="w-24 h-[134px] rounded-xl object-cover flex-shrink-0"
                                     style="box-shadow: var(--shadow-card);" />
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm mb-1" style="color: var(--text-primary);" x-text="sellCard.card_name"></p>
                                    <p class="text-xs mb-2" style="color: var(--text-muted);" x-text="sellCard.rarity || 'Common'"></p>
                                    <div class="flex items-center gap-1.5 mb-3">
                                        <span class="text-xs font-medium" style="color: var(--text-muted);">En stock :</span>
                                        <span class="text-xs font-bold" style="color: var(--text-primary);" x-text="'x' + sellCard.quantity"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" style="color: #d4a843;" fill="currentColor" viewBox="0 0 512 512"><path d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .3-24.5 .8C124.3 109.2 96.4 88 96.4 64c0-35.3 57.3-64 128-64s128 28.7 128 64c0 5.3-1 10.4-2.8 15.3C430.1 82.6 512 78 512 80z"/></svg>
                                        <span class="text-sm font-bold" style="color: #d4a843;" x-text="sellPrice + ' / carte'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl mb-5" style="background: var(--bg-surface); border: 1px solid var(--border);">
                                <label class="text-xs font-medium block mb-2" style="color: var(--text-muted);">Quantité à vendre</label>
                                <div class="flex items-center gap-3">
                                    <button @click="sellQty = Math.max(1, sellQty - 1)" class="w-8 h-8 rounded-lg flex items-center justify-center text-lg font-bold"
                                            style="background: var(--bg-card); color: var(--text-primary); border: 1px solid var(--border);">−</button>
                                    <span class="text-lg font-bold flex-1 text-center" style="color: var(--text-primary);" x-text="sellQty"></span>
                                    <button @click="sellQty = Math.min(sellMaxQty, sellQty + 1)" class="w-8 h-8 rounded-lg flex items-center justify-center text-lg font-bold"
                                            style="background: var(--bg-card); color: var(--text-primary); border: 1px solid var(--border);">+</button>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-3" style="border-top: 1px solid var(--border);">
                                    <span class="text-xs font-medium" style="color: var(--text-muted);">Total</span>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" style="color: #d4a843;" fill="currentColor" viewBox="0 0 512 512"><path d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .3-24.5 .8C124.3 109.2 96.4 88 96.4 64c0-35.3 57.3-64 128-64s128 28.7 128 64c0 5.3-1 10.4-2.8 15.3C430.1 82.6 512 78 512 80z"/></svg>
                                        <span class="text-sm font-bold" style="color: #22c55e;" x-text="(sellQty * sellPrice).toLocaleString('fr-FR') + ' PokéTokens'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button @click="sellModal = false" class="btn btn-surface flex-1">Annuler</button>
                                <form action="{{ route('collection.sell') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="card_id" :value="sellCard.card_id" />
                                    <input type="hidden" name="quantity" :value="sellQty" />
                                    <button type="submit" class="btn w-full"
                                            style="background: linear-gradient(135deg, #d4a843, #f59e0b); color: #000; font-weight: 700;">
                                        Vendre
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
                <button @click="sellModal = false"
                        class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center"
                        style="background: rgba(255,255,255,0.08); color: var(--text-muted);">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
