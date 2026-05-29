<x-app-layout>
    <x-slot:title>Boutique — PokeHub</x-slot:title>

    @php
        $activeTitle  = $user->active_title;
        $activeFrame  = $user->active_frame;
        $activeSleeve = $user->active_sleeve;
    @endphp

    <div class="min-h-full" x-data="{ tab: 'title', confirmBuy: null }">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <div class="page-hero px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <img src="{{ asset('images/bannieredarkrai.webp') }}" alt="" class="page-hero-bg" />
            <div class="page-hero-overlay"></div>
            <div class="relative z-10">
                <span class="label-xs block mb-3" style="color: var(--text-primary);">Boutique</span>
                <h1 class="text-3xl font-black mb-2 tracking-tight" style="color: var(--text-primary);">Boutique PokéTokens</h1>
                <p class="text-sm mb-6" style="color: var(--text-muted);">Personnalise ton profil avec des titres, cadres et sleeves uniques</p>

                {{-- Balance --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                         style="background: rgba(212,168,67,0.1); border: 1px solid rgba(212,168,67,0.25);">
                        <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-5 h-5 object-contain" />
                        <span class="text-lg font-extrabold" style="color: #d4a843;">{{ number_format($user->poketokens, 0, ',', ' ') }}</span>
                        <span class="text-xs font-medium" style="color: var(--text-muted);">PokéTokens</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Category tabs ──────────────────────────────────────── --}}
        <div class="px-10 py-4 flex items-center gap-2 flex-wrap" style="border-bottom: 1px solid var(--border);">
            <button @click="tab = 'title'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all cursor-pointer"
                    :style="tab === 'title'
                        ? 'background: linear-gradient(135deg, rgba(255,215,0,0.15), rgba(245,158,11,0.15)); color: #ffd700; border: 1px solid rgba(255,215,0,0.3);'
                        : 'background: transparent; color: var(--text-muted); border: 1px solid var(--border);'">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 576 512"><path d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6H426.6c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>
                    Titres
                </span>
            </button>
            <button @click="tab = 'frame'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all cursor-pointer"
                    :style="tab === 'frame'
                        ? 'background: linear-gradient(135deg, rgba(163,130,255,0.15), rgba(139,92,246,0.15)); color: #a382ff; border: 1px solid rgba(163,130,255,0.3);'
                        : 'background: transparent; color: var(--text-muted); border: 1px solid var(--border);'">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 448 512"><path d="M0 96C0 60.7 28.7 32 64 32H384c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM48 368v48c0 8.8 7.2 16 16 16h48V368H48zm80 64H320V368H128v64zm224 0h48c8.8 0 16-7.2 16-16V368H352v64zM448 144c0-8.8-7.2-16-16-16H352v64h96V144zM320 128H128v64H320V128zM112 128H64c-8.8 0-16 7.2-16 16v48h64V128zM48 240v80H112V240H48zm80 80H320V240H128v80zm224-80v80h96V240H352z"/></svg>
                    Cadres
                </span>
            </button>
            <button @click="tab = 'sleeve'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all cursor-pointer"
                    :style="tab === 'sleeve'
                        ? 'background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(245,158,11,0.15)); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);'
                        : 'background: transparent; color: var(--text-muted); border: 1px solid var(--border);'">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 576 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm80 256h64c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16c0-44.2 35.8-80 80-80zm-32-96a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zm256-32H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
                    Sleeves
                </span>
            </button>
        </div>

        {{-- ── Items grid ─────────────────────────────────────────── --}}
        <div class="px-10 py-10">

            {{-- TITLES ──────────────────────────────────────────────── --}}
            <div x-show="tab === 'title'" x-transition.opacity>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-lg font-bold" style="color: var(--text-primary);">Titres de Dresseur</h2>
                    <span class="list-pill">{{ ($items['title'] ?? collect())->count() }}</span>
                </div>
                <p class="text-sm mb-8" style="color: var(--text-muted);">Affiche un titre prestigieux sous ton nom dans la barre latérale</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($items['title'] ?? [] as $item)
                        @php
                            $isOwned   = in_array($item->id, $owned);
                            $isActive  = $activeTitle === $item->slug;
                            $gradient  = $item->data['gradient'] ?? 'linear-gradient(135deg, #9CA3AF, #6B7280)';
                            $shadow    = $item->data['shadow'] ?? 'none';
                        @endphp
                        <div class="relative rounded-2xl overflow-hidden transition-all {{ $isActive ? 'ring-2 ring-offset-2' : '' }}"
                             style="background: var(--bg-card); border: 1px solid {{ $isActive ? 'rgba(255,215,0,0.4)' : 'var(--border)' }}; {{ $isActive ? 'ring-color: #ffd700; --tw-ring-offset-color: var(--bg-base);' : '' }}">

                            {{-- Preview area --}}
                            <div class="px-6 pt-6 pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex-1">
                                        <p class="text-lg font-extrabold mb-1" style="background: {{ $gradient }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow({{ $shadow }});">
                                            {{ $item->name }}
                                        </p>
                                        <p class="text-xs" style="color: var(--text-muted);">{{ $item->description }}</p>
                                    </div>
                                    @if($isActive)
                                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold"
                                              style="background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3);">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                            Actif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="px-6 pb-5 flex items-center justify-between">
                                @if($item->price === 0)
                                    <span class="text-xs font-bold" style="color: #22c55e;">Gratuit</span>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-3.5 h-3.5 object-contain" />
                                        <span class="text-sm font-bold" style="color: #d4a843;">{{ number_format($item->price, 0, ',', ' ') }}</span>
                                    </div>
                                @endif

                                <div class="flex gap-2">
                                    @if(!$isOwned && $item->price > 0)
                                        <button @click="confirmBuy = @js(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'url' => route('shop.buy', $item)])"
                                                class="btn btn-sm cursor-pointer"
                                                style="background: linear-gradient(135deg, #d4a843, #f59e0b); color: #000; font-weight: 700;">
                                            Acheter
                                        </button>
                                    @elseif($isActive)
                                        <form action="{{ route('shop.unequip', 'title') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-surface btn-sm cursor-pointer">Retirer</button>
                                        </form>
                                    @else
                                        <form action="{{ route('shop.equip', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm cursor-pointer">Équiper</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- FRAMES ──────────────────────────────────────────────── --}}
            <div x-show="tab === 'frame'" x-transition.opacity x-cloak>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-lg font-bold" style="color: var(--text-primary);">Cadres de Profil</h2>
                    <span class="list-pill">{{ ($items['frame'] ?? collect())->count() }}</span>
                </div>
                <p class="text-sm mb-8" style="color: var(--text-muted);">Personnalise le cadre autour de ton avatar</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($items['frame'] ?? [] as $item)
                        @php
                            $isOwned   = in_array($item->id, $owned);
                            $isActive  = $activeFrame === $item->slug;
                            $borderCss = $item->data['border'] ?? '2px solid var(--border)';
                            $shadowCss = $item->data['shadow'] ?? 'none';
                        @endphp
                        <div class="relative rounded-2xl overflow-hidden transition-all"
                             style="background: var(--bg-card); border: 1px solid {{ $isActive ? 'rgba(163,130,255,0.4)' : 'var(--border)' }};">

                            <div class="p-6 flex items-center gap-5">
                                {{-- Frame preview --}}
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="border: {{ $borderCss }}; box-shadow: {{ $shadowCss }}; background: var(--bg-surface);">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar }}" alt="" class="w-12 h-12 rounded-lg object-contain" />
                                    @else
                                        <span class="text-lg font-bold" style="color: var(--text-primary);">{{ strtoupper(substr(auth()->user()->name ?? 'D', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm mb-0.5" style="color: var(--text-primary);">{{ $item->name }}</p>
                                    <p class="text-xs mb-3" style="color: var(--text-muted);">{{ $item->description }}</p>

                                    <div class="flex items-center justify-between">
                                        @if($item->price === 0)
                                            <span class="text-xs font-bold" style="color: #22c55e;">Gratuit</span>
                                        @else
                                            <div class="flex items-center gap-1.5">
                                                <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-3.5 h-3.5 object-contain" />
                                                <span class="text-sm font-bold" style="color: #d4a843;">{{ number_format($item->price, 0, ',', ' ') }}</span>
                                            </div>
                                        @endif

                                        <div class="flex gap-2">
                                            @if(!$isOwned && $item->price > 0)
                                                <button @click="confirmBuy = @js(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'url' => route('shop.buy', $item)])"
                                                        class="btn btn-sm cursor-pointer"
                                                        style="background: linear-gradient(135deg, #d4a843, #f59e0b); color: #000; font-weight: 700;">
                                                    Acheter
                                                </button>
                                            @elseif($isActive)
                                                <form action="{{ route('shop.unequip', 'frame') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-surface btn-sm cursor-pointer">Retirer</button>
                                                </form>
                                            @else
                                                <form action="{{ route('shop.equip', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm cursor-pointer">Équiper</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($isActive)
                                <div class="absolute top-3 right-3">
                                    <span class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold"
                                          style="background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3);">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                        Actif
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SLEEVES ─────────────────────────────────────────────── --}}
            <div x-show="tab === 'sleeve'" x-transition.opacity x-cloak>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-lg font-bold" style="color: var(--text-primary);">Sleeves de Cartes</h2>
                    <span class="list-pill">{{ ($items['sleeve'] ?? collect())->count() }}</span>
                </div>
                <p class="text-sm mb-8" style="color: var(--text-muted);">Change le dos de tes cartes dans les boosters</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    @foreach($items['sleeve'] ?? [] as $item)
                        @php
                            $isOwned   = in_array($item->id, $owned);
                            $isActive  = $activeSleeve === $item->slug;
                            $bgCss     = $item->data['background'] ?? 'linear-gradient(135deg, #1a1d25, #2a2d35)';
                            $colorCss  = $item->data['color'] ?? '#6B7280';
                        @endphp
                        <div class="relative rounded-2xl overflow-hidden transition-all"
                             style="background: var(--bg-card); border: 1px solid {{ $isActive ? 'rgba(239,68,68,0.4)' : 'var(--border)' }};">

                            {{-- Sleeve preview (card back shape) --}}
                            <div class="p-5 flex justify-center">
                                <div class="w-24 rounded-xl overflow-hidden" style="aspect-ratio: 63/88; border: 2px solid {{ $colorCss }}40;">
                                    <div class="w-full h-full flex items-center justify-center" style="background: {{ $bgCss }};">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                             style="background: rgba(255,255,255,0.08); border: 2px solid {{ $colorCss }}60;">
                                            <svg class="w-5 h-5" style="color: {{ $colorCss }};" fill="currentColor" viewBox="0 0 512 512"><path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0z"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-4 pb-4">
                                <p class="font-bold text-sm mb-0.5 text-center" style="color: var(--text-primary);">{{ $item->name }}</p>
                                <p class="text-[11px] text-center mb-3" style="color: var(--text-muted);">{{ $item->description }}</p>

                                <div class="flex items-center justify-between">
                                    @if($item->price === 0)
                                        <span class="text-xs font-bold" style="color: #22c55e;">Gratuit</span>
                                    @else
                                        <div class="flex items-center gap-1">
                                            <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-3 h-3 object-contain" />
                                            <span class="text-xs font-bold" style="color: #d4a843;">{{ number_format($item->price, 0, ',', ' ') }}</span>
                                        </div>
                                    @endif

                                    @if(!$isOwned && $item->price > 0)
                                        <button @click="confirmBuy = @js(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'url' => route('shop.buy', $item)])"
                                                class="btn btn-sm text-[11px] cursor-pointer"
                                                style="background: linear-gradient(135deg, #d4a843, #f59e0b); color: #000; font-weight: 700;">
                                            Acheter
                                        </button>
                                    @elseif($isActive)
                                        <form action="{{ route('shop.unequip', 'sleeve') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-surface btn-sm text-[11px] cursor-pointer">Retirer</button>
                                        </form>
                                    @else
                                        <form action="{{ route('shop.equip', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm text-[11px] cursor-pointer">Équiper</button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if($isActive)
                                <div class="absolute top-3 right-3">
                                    <span class="flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold"
                                          style="background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3);">
                                        Actif
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Buy confirmation popup ─────────────────────────────── --}}
        <div x-show="confirmBuy" x-transition.opacity x-cloak
             @click.self="confirmBuy = null" @keydown.escape.window="confirmBuy = null"
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);">
            <div x-show="confirmBuy" x-transition.scale.origin.center
                 class="relative w-full max-w-sm mx-4 rounded-2xl overflow-hidden"
                 style="background: var(--bg-card); border: 1px solid rgba(212,168,67,0.3);">
                <div class="h-1.5" style="background: linear-gradient(90deg, #d4a843, #f59e0b);"></div>
                <div class="p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                         style="background: linear-gradient(135deg, rgba(212,168,67,0.2), rgba(245,158,11,0.2)); border: 1px solid rgba(212,168,67,0.3);">
                        <svg class="w-7 h-7" style="color: #d4a843;" fill="currentColor" viewBox="0 0 576 512"><path d="M253.3 35.1c6.1-11.8 1.5-26.3-10.2-32.4s-26.3-1.5-32.4 10.2L117.6 192 32 192c-17.7 0-32 14.3-32 32s14.3 32 32 32L0 448c0 17.7 14.3 32 32 32H576c17.7 0 32-14.3 32-32l-32-192c17.7 0 32-14.3 32-32s-14.3-32-32-32H490.4L397.3 12.9C391.2 1.2 376.7-3.4 365 2.7s-16.3 20.6-10.2 32.4L430.4 192H177.6L253.3 35.1zM176 288v96c0 13.3-10.7 24-24 24s-24-10.7-24-24V288c0-13.3 10.7-24 24-24s24 10.7 24 24zm112 0v96c0 13.3-10.7 24-24 24s-24-10.7-24-24V288c0-13.3 10.7-24 24-24s24 10.7 24 24zm112 0v96c0 13.3-10.7 24-24 24s-24-10.7-24-24V288c0-13.3 10.7-24 24-24s24 10.7 24 24z"/></svg>
                    </div>
                    <h3 class="text-lg font-extrabold mb-1" style="color: var(--text-primary);">Confirmer l'achat</h3>
                    <p class="text-sm mb-4" style="color: var(--text-muted);" x-text="confirmBuy?.name"></p>

                    <div class="p-4 rounded-xl mb-5" style="background: var(--bg-surface); border: 1px solid var(--border);">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium" style="color: var(--text-muted);">Prix</span>
                            <div class="flex items-center gap-1.5">
                                <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-4 h-4 object-contain" />
                                <span class="text-sm font-bold" style="color: #d4a843;" x-text="confirmBuy?.price?.toLocaleString('fr-FR')"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium" style="color: var(--text-muted);">Ton solde</span>
                            <span class="text-sm font-bold" style="color: var(--text-primary);">{{ number_format($user->poketokens, 0, ',', ' ') }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2" style="border-top: 1px solid var(--border);">
                            <span class="text-xs font-medium" style="color: var(--text-muted);">Après achat</span>
                            <span class="text-sm font-bold"
                                  :style="({{ $user->poketokens }} - (confirmBuy?.price || 0)) >= 0 ? 'color: #22c55e;' : 'color: #f87171;'"
                                  x-text="({{ $user->poketokens }} - (confirmBuy?.price || 0)).toLocaleString('fr-FR')"></span>
                        </div>
                    </div>

                    <template x-if="{{ $user->poketokens }} >= (confirmBuy?.price || 0)">
                        <div class="flex gap-3">
                            <button @click="confirmBuy = null" class="btn btn-surface flex-1">Annuler</button>
                            <form :action="confirmBuy?.url" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn w-full cursor-pointer"
                                        style="background: linear-gradient(135deg, #d4a843, #f59e0b); color: #000; font-weight: 700;">
                                    Acheter
                                </button>
                            </form>
                        </div>
                    </template>
                    <template x-if="{{ $user->poketokens }} < (confirmBuy?.price || 0)">
                        <div>
                            <p class="text-xs mb-4" style="color: #f87171;">Solde insuffisant pour cet achat.</p>
                            <button @click="confirmBuy = null" class="btn btn-surface w-full">Fermer</button>
                        </div>
                    </template>
                </div>
                <button @click="confirmBuy = null"
                        class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center"
                        style="background: rgba(255,255,255,0.08); color: var(--text-muted);">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
