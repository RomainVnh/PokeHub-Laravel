<x-app-layout>
    <x-slot:title>Estimation de prix — PokeHub</x-slot:title>

    <div class="min-h-full" x-data="priceSearch()">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <div class="page-hero px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <img src="{{ asset('images/banniere4.jpg') }}" alt="" class="page-hero-bg" />
            <div class="page-hero-overlay"></div>
            <div class="relative z-10">
                <span class="label-xs block mb-3" style="color: var(--text-primary);">Estimation</span>
                <h1 class="text-3xl font-black mb-2 tracking-tight" style="color: var(--text-primary);">Prix des cartes</h1>
                <p class="text-sm mb-8" style="color: var(--text-muted);">Recherche un Pokemon pour voir les estimations de prix de ses cartes</p>

                {{-- Search bar --}}
                <div class="relative max-w-xl">
                    <svg class="absolute w-5 h-5 pointer-events-none" style="left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" fill="currentColor" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                    <input x-model="query" @input.debounce.400ms="searchCards()" type="text"
                           placeholder="Rechercher un Pokemon (ex: Charizard, Pikachu...)"
                           class="input-dark text-base" style="padding: 14px 16px 14px 48px; width: 100%;" />
                    <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 animate-spin" style="color: var(--gold);" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Suggestions ─────────────────────────────────────────── --}}
        <div class="px-10 py-10">
            <template x-if="results.length === 0 && !loading && !query">
                <div>
                    <h2 class="text-lg font-bold mb-6" style="color: var(--text-primary);">Pokemon populaires</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($popularPokemon as $name)
                            <button @click="query = '{{ $name }}'; searchCards()"
                                    class="px-5 py-3 rounded-xl text-sm font-bold transition-all"
                                    style="background: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary);"
                                    onmouseover="this.style.borderColor='var(--gold)'; this.style.color='var(--gold)'"
                                    onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-primary)'">
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </template>

            {{-- Results --}}
            <template x-if="results.length > 0">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold" style="color: var(--text-primary);">
                            <span x-text="results.length"></span> carte(s) trouvée(s)
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                        <template x-for="card in results" :key="card.id">
                            <div class="edition-card group">
                                <div class="aspect-[63/88] overflow-hidden rounded-t-xl">
                                    <img :src="card.image" :alt="card.name"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.style.opacity='0.3'" />
                                </div>
                                <div class="p-3 space-y-1.5">
                                    <p class="text-sm font-bold line-clamp-1" style="color: var(--text-primary);" x-text="card.name"></p>
                                    <p class="text-[11px]" style="color: var(--text-muted);" x-text="card.set + ' · ' + card.rarity"></p>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-lg font-black" style="color: var(--gold);"
                                              x-text="card.price.toFixed(2) + ' €'"></span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                              style="background: rgba(212,168,67,0.15); color: var(--gold);">Estimation</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- No results --}}
            <template x-if="results.length === 0 && !loading && query.length >= 2">
                <div class="text-center py-16">
                    <p class="text-sm" style="color: var(--text-muted);">Aucune carte trouvée pour "<span x-text="query"></span>"</p>
                </div>
            </template>
        </div>
    </div>

    <script>
    function priceSearch() {
        return {
            query: '',
            results: [],
            loading: false,
            async searchCards() {
                if (this.query.length < 2) { this.results = []; return; }
                this.loading = true;
                try {
                    const res = await fetch('/api/prices/search?q=' + encodeURIComponent(this.query));
                    this.results = await res.json();
                } catch(e) {
                    this.results = [];
                }
                this.loading = false;
            }
        };
    }
    </script>
</x-app-layout>
