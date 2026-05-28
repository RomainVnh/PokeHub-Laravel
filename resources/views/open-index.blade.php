<x-app-layout>
    <x-slot:title>Boosters — PokeHub</x-slot:title>

    <div class="min-h-full" x-data="{ search: '', premiumModal: false, premiumSetId: '', premiumSetName: '' }">

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
                            <button @click.prevent.stop="premiumSetId = '{{ $set['id'] }}'; premiumSetName = '{{ addslashes($set['name']) }}'; premiumModal = true"
                                    class="absolute top-2 right-2 w-8 h-8 rounded-lg flex items-center justify-center z-10 transition-all opacity-0 group-hover:opacity-100 cursor-pointer"
                                    style="background: linear-gradient(135deg, #a382ff, #d4a843); border: none;"
                                    title="Booster Premium">
                                <svg class="w-4 h-4" fill="white" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3L288.1 439.8 416.2 508.3c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329 542.4 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L380.9 150.3 316.9 18z"/></svg>
                            </button>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
        {{-- ═══ Premium confirmation modal ═══════════════════════════ --}}
        @auth
            <div x-show="premiumModal" x-transition.opacity
                 @click.self="premiumModal = false" @keydown.escape.window="premiumModal = false"
                 class="fixed inset-0 z-50 flex items-center justify-center"
                 style="background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);">
                <div x-show="premiumModal" x-transition.scale.origin.center
                     class="relative w-full max-w-md mx-4 rounded-2xl overflow-hidden"
                     style="background: var(--bg-card); border: 1px solid rgba(163,130,255,0.3);">

                    <div class="h-1.5" style="background: linear-gradient(90deg, #a382ff, #d4a843);"></div>

                    <div class="p-6 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5"
                             style="background: linear-gradient(135deg, #a382ff, #d4a843); box-shadow: 0 8px 24px rgba(163,130,255,0.3);">
                            <svg class="w-8 h-8" fill="white" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3L288.1 439.8 416.2 508.3c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329 542.4 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L380.9 150.3 316.9 18z"/></svg>
                        </div>

                        <h3 class="text-xl font-extrabold mb-2" style="color: var(--text-primary);">Booster Premium</h3>
                        <p class="text-sm mb-1" style="color: var(--text-secondary);" x-text="premiumSetName"></p>

                        <div class="my-5 p-4 rounded-xl" style="background: var(--bg-surface); border: 1px solid var(--border);">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium" style="color: var(--text-muted);">Coût</span>
                                @if($canFreeOpen ?? false)
                                    <span class="text-sm font-bold" style="color: #22c55e;">Gratuit (1/jour)</span>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" style="color: #d4a843;" fill="currentColor" viewBox="0 0 512 512"><path d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .3-24.5 .8C124.3 109.2 96.4 88 96.4 64c0-35.3 57.3-64 128-64s128 28.7 128 64c0 5.3-1 10.4-2.8 15.3C430.1 82.6 512 78 512 80zM224.5 161.7c6.8-.4 13.7-.7 20.8-.9C266.3 142.3 304.6 128 352 128c38 0 72.6 9.4 99.7 24.8C492.3 170.5 512 192 512 214.7V256c0 46-104.3 80-192 80c-14.1 0-27.8-.8-41-2.2c1.3-5 2.4-10.2 3.3-15.5C364.6 307.9 432 276.8 432 256c0-13.3-16.7-25.6-44.1-35C380.1 236.4 368 256 352 272c-5.2 5.2-10.8 10-16.8 14.3C393.8 296.8 432 280 432 256v-42.7c0-8.3-7.2-17.4-20-25.9c-15-10-35.6-18-60.3-23.2C327.6 155.5 296 152 264 154.3c-11.7 .8-23 2.2-33.5 4V161.7zM96 256c0-53 43-96 96-96s96 43 96 96-43 96-96 96-96-43-96-96zm96-32a32 32 0 1 0 0 64 32 32 0 1 0 0-64z"/></svg>
                                        <span class="text-sm font-bold" style="color: #d4a843;">500 PokéTokens</span>
                                    </div>
                                @endif
                            </div>
                            @if(!($canFreeOpen ?? false))
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium" style="color: var(--text-muted);">Ton solde</span>
                                    <span class="text-sm font-bold" style="color: var(--text-primary);">{{ number_format(auth()->user()->poketokens, 0, ',', ' ') }} PokéTokens</span>
                                </div>
                                <div class="flex items-center justify-between mt-2 pt-2" style="border-top: 1px solid var(--border);">
                                    <span class="text-xs font-medium" style="color: var(--text-muted);">Après achat</span>
                                    <span class="text-sm font-bold" style="color: {{ auth()->user()->poketokens >= 500 ? '#22c55e' : '#f87171' }};">{{ number_format(max(0, auth()->user()->poketokens - 500), 0, ',', ' ') }} PokéTokens</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2 text-left mb-6">
                            <div class="flex items-center gap-2.5 text-xs" style="color: var(--text-secondary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #a382ff;" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                1 carte Ultra Rare, EX ou Full Art garantie
                            </div>
                            <div class="flex items-center gap-2.5 text-xs" style="color: var(--text-secondary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #a382ff;" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                Taux 4.8% secret, 14.4% illustration, 28.8% ultra
                            </div>
                            <div class="flex items-center gap-2.5 text-xs" style="color: var(--text-secondary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #a382ff;" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                3 à 5 emplacements rares (jusqu'au God Pack)
                            </div>
                        </div>

                        @if($canFreeOpen ?? false)
                            <div class="flex gap-3">
                                <button @click="premiumModal = false" class="btn btn-surface flex-1">Annuler</button>
                                <a :href="'/open/' + premiumSetId + '/premium'"
                                   class="btn flex-1 text-center"
                                   style="background: linear-gradient(135deg, #a382ff, #d4a843); color: white; font-weight: 700;">
                                    Ouvrir
                                </a>
                            </div>
                        @elseif(auth()->user()->poketokens >= 500)
                            <div class="flex gap-3">
                                <button @click="premiumModal = false" class="btn btn-surface flex-1">Annuler</button>
                                <form :action="'/open/' + premiumSetId + '/premium'" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="btn w-full"
                                            style="background: linear-gradient(135deg, #a382ff, #d4a843); color: white; font-weight: 700;">
                                        Payer & Ouvrir
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-xs mb-4 text-center" style="color: #f87171;">Solde insuffisant pour ouvrir un booster premium.</p>
                            <button @click="premiumModal = false" class="btn btn-surface w-full">Fermer</button>
                        @endif
                    </div>

                    <button @click="premiumModal = false"
                            class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center"
                            style="background: rgba(255,255,255,0.08); color: var(--text-muted);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </button>
                </div>
            </div>
        @endauth
    </div>
</x-app-layout>
