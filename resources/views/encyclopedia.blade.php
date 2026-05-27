<x-app-layout>
    <x-slot:title>Encyclopédie — PokeHub</x-slot:title>

    <div class="min-h-full" x-data="{ search: '', selectedSeries: 'all', sortBy: 'newest', minCards: 0 }">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <div class="page-hero px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <img src="{{ asset('images/bannieredarkrai.webp') }}" alt="" class="page-hero-bg" />
            <div class="page-hero-overlay"></div>
            <div class="relative z-10">
            <span class="label-xs block mb-3" style="color: var(--text-primary);">Explorer</span>
            <h1 class="text-3xl font-black mb-2 tracking-tight" style="color: var(--text-primary);">Encyclopédie</h1>
            <p class="text-sm mb-8" style="color: var(--text-muted);">{{ count($sets) }} éditions disponibles</p>

            <div class="flex gap-3 flex-wrap max-w-2xl">
                <div class="relative flex-1 min-w-[240px]">
                    <svg class="absolute w-4 h-4 pointer-events-none" style="left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" fill="currentColor" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                    <input x-model="search" type="text" placeholder="Rechercher une édition..."
                           class="input-dark" style="padding-left: 40px;" />
                </div>
                <select x-model="selectedSeries"
                        class="input-dark w-auto min-w-[180px] cursor-pointer">
                    <option value="all">Toutes les séries</option>
                    @foreach(array_keys($series) as $seriesName)
                        <option value="{{ $seriesName }}">{{ $seriesName }}</option>
                    @endforeach
                </select>
                <select x-model="sortBy" class="input-dark w-auto min-w-[160px] cursor-pointer">
                    <option value="newest">Plus récentes</option>
                    <option value="oldest">Plus anciennes</option>
                    <option value="most-cards">Plus de cartes</option>
                    <option value="least-cards">Moins de cartes</option>
                </select>
                <select x-model="minCards" class="input-dark w-auto min-w-[140px] cursor-pointer">
                    <option value="0">Toutes tailles</option>
                    <option value="50">50+ cartes</option>
                    <option value="100">100+ cartes</option>
                    <option value="150">150+ cartes</option>
                    <option value="200">200+ cartes</option>
                </select>
            </div>
            </div>
        </div>

        {{-- ── Sets grid ──────────────────────────────────────────── --}}
        <div class="px-10 py-10 space-y-12">
            @foreach($series as $seriesName => $serieSets)
                <div x-show="selectedSeries === 'all' || selectedSeries === '{{ addslashes($seriesName) }}'"
                     x-cloak>
                    <div class="flex items-center gap-3 mb-5">
                        <h2 class="text-base font-bold" style="color: var(--text-primary);">{{ $seriesName }}</h2>
                        <span class="list-pill">{{ count($serieSets) }}</span>
                        <div class="flex-1 h-px" style="background: var(--border);"></div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                        @foreach($serieSets as $set)
                            @php
                                $collected = $collectionProgress[$set['id']] ?? 0;
                                $total     = $set['printedTotal'] ?: 1;
                                $pct       = Auth::check() ? round(($collected / $total) * 100) : -1;
                                $noCards   = Auth::check() && $collected === 0;
                            @endphp
                            <div x-show="(!search || '{{ strtolower(addslashes($set['name'])) }}'.includes(search.toLowerCase())) && {{ $set['printedTotal'] }} >= minCards"
                                 class="edition-card group {{ $noCards ? 'edition-not-collected' : '' }}">
                                <div class="h-28 flex items-center justify-center p-4"
                                     style="background: linear-gradient(145deg, rgba(212,168,67,0.04) 0%, rgba(139,92,246,0.04) 100%);">
                                    <img src="{{ $set['images']['logo'] ?? '' }}"
                                         alt="{{ $set['name'] }}"
                                         class="max-h-20 max-w-full object-contain group-hover:scale-110 transition-transform duration-300"
                                         loading="lazy" onerror="this.style.opacity='0'" />
                                </div>
                                <div class="p-4 space-y-2">
                                    <p class="text-sm font-bold line-clamp-2 leading-tight" style="color: var(--text-primary);">{{ $set['name'] }}</p>
                                    <p class="text-[11px]" style="color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($set['releaseDate'])->format('Y') }} · {{ $set['printedTotal'] }} cartes
                                    </p>

                                    {{-- Collection progress bar --}}
                                    @auth
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.08);">
                                                <div class="h-full rounded-full transition-all" style="width: {{ $pct }}%; background: linear-gradient(90deg, var(--gold), #f59e0b);"></div>
                                            </div>
                                            <span class="text-[10px] font-bold whitespace-nowrap" style="color: {{ $pct > 0 ? 'var(--gold)' : 'var(--text-muted)' }};">{{ $collected }}/{{ $total }}</span>
                                        </div>
                                    @endauth

                                    <div class="flex gap-2 pt-1">
                                        <a href="{{ route('set.show', $set['id']) }}"
                                           class="flex-1 text-center text-[11px] font-bold rounded-lg py-2 transition-colors"
                                           style="color: var(--text-primary); background: var(--accent-bg);"
                                           onmouseover="this.style.background='var(--accent-glow)'"
                                           onmouseout="this.style.background='var(--accent-bg)'">
                                            Voir
                                        </a>
                                        <a href="{{ route('open.booster', $set['id']) }}"
                                           class="btn btn-primary btn-sm flex-1 text-[11px]">
                                            Ouvrir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
