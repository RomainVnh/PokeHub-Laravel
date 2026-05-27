<x-app-layout>
    <x-slot:title>Ma Collection — PokeHub</x-slot:title>

    <div class="min-h-full" x-data="{
        search: '',
        filterSet: 'all',
        filterRarity: 'all',
        sortBy: 'recent'
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
                                <div x-show="(filterRarity === 'all' || '{{ addslashes($card['rarity'] ?? '') }}' === filterRarity) && (!search || '{{ strtolower(addslashes($card['card_name'])) }}'.includes(search.toLowerCase()))"
                                     class="relative group">
                                    <div class="card-item rounded-lg overflow-hidden border transition-all duration-200"
                                         style="border-color: var(--border); background: var(--bg-card);">
                                        <img src="{{ $imgUrl }}"
                                             alt="{{ $card['card_name'] }}"
                                             class="w-full aspect-[63/88] object-cover"
                                             loading="lazy"
                                             onerror="this.style.opacity='0.3'" />
                                    </div>
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
    </div>
</x-app-layout>
