<x-app-layout>
    <x-slot:title>Boosters — PokeHub</x-slot:title>

    <div class="min-h-full" x-data="{ search: '' }">

        <div class="page-hero px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <img src="{{ asset('images/rayquaza.jpg') }}" alt="" class="page-hero-bg" />
            <div class="page-hero-overlay"></div>
            <div class="relative z-10">
            <span class="label-xs block mb-3" style="color: var(--gold);">Ouvrir</span>
            <h1 class="text-3xl font-black mb-2 tracking-tight" style="color: var(--text-primary);">Boosters</h1>
            <p class="text-sm mb-8" style="color: var(--text-muted);">Choisis une édition et ouvre un booster de 5 cartes.</p>

            <div class="relative max-w-sm">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="currentColor" viewBox="0 0 512 512">
                    <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                </svg>
                <input x-model="search" type="text" placeholder="Rechercher une édition..." class="input-dark pl-11" />
            </div>
            </div>
        </div>

        {{-- Premium banner --}}
        @auth
            @php
                $user = auth()->user();
                $canFreeOpen = !$user->last_premium_booster_at || !$user->last_premium_booster_at->isToday();
            @endphp
            <div class="mx-10 mt-8 p-5 rounded-2xl relative overflow-hidden" style="background: linear-gradient(135deg, rgba(163,130,255,0.12) 0%, rgba(212,168,67,0.12) 100%); border: 1px solid rgba(163,130,255,0.25);">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #a382ff, #d4a843);">
                        <svg class="w-6 h-6" fill="white" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3L288.1 439.8 416.2 508.3c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329 542.4 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L380.9 150.3 316.9 18z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold" style="color: var(--text-primary);">Booster Premium</h3>
                        <p class="text-xs mt-0.5" style="color: var(--text-muted);">
                            Au moins 1 carte Ultra Rare, EX ou Full Art garantie ! Choisis une édition ci-dessous.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        @if($canFreeOpen)
                            <span class="text-xs font-bold px-3 py-1.5 rounded-lg" style="background: rgba(34,197,94,0.15); color: #22c55e;">1 gratuit disponible</span>
                        @else
                            <span class="text-xs font-bold px-3 py-1.5 rounded-lg" style="background: rgba(212,168,67,0.15); color: #d4a843;">500 PokéTokens</span>
                        @endif
                    </div>
                </div>
            </div>
        @endauth

        <div class="px-10 py-10">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                @foreach($sets as $set)
                    <div x-show="!search || '{{ strtolower(addslashes($set['name'])) }}'.includes(search.toLowerCase())"
                         x-cloak
                         class="edition-card group relative">
                        <a href="{{ route('open.booster', $set['id']) }}">
                            <div class="h-28 flex items-center justify-center p-4"
                                 style="background: linear-gradient(145deg, rgba(212,168,67,0.04) 0%, rgba(139,92,246,0.04) 100%);">
                                <img src="{{ $set['images']['logo'] ?? '' }}" alt="{{ $set['name'] }}"
                                     class="max-h-20 max-w-full object-contain group-hover:scale-110 transition-transform duration-300"
                                     loading="lazy" onerror="this.style.opacity='0'" />
                            </div>
                            <div class="p-4">
                                <p class="text-sm font-bold truncate mb-1" style="color: var(--text-primary);">{{ $set['name'] }}</p>
                                <p class="text-[11px]" style="color: var(--text-muted);">{{ $set['printedTotal'] }} cartes</p>
                            </div>
                        </a>
                        @auth
                            <a href="{{ route('open.booster.premium', $set['id']) }}"
                               class="absolute top-2 right-2 w-8 h-8 rounded-lg flex items-center justify-center z-10 transition-all opacity-0 group-hover:opacity-100"
                               style="background: linear-gradient(135deg, #a382ff, #d4a843);"
                               title="Booster Premium">
                                <svg class="w-4 h-4" fill="white" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3L288.1 439.8 416.2 508.3c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329 542.4 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L380.9 150.3 316.9 18z"/></svg>
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
