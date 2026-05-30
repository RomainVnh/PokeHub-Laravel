<x-app-layout>
    <x-slot:title>Mon Profil — PokeHub</x-slot:title>

    @php
        $frameBorder = $activeFrame ? ($activeFrame->data['border'] ?? '2px solid var(--border)') : '2px solid var(--border)';
        $frameShadow = $activeFrame ? ($activeFrame->data['shadow'] ?? 'none') : 'none';
        $titleGradient = $activeTitle ? ($activeTitle->data['gradient'] ?? 'linear-gradient(135deg, #9CA3AF, #6B7280)') : null;
        $titleShadow   = $activeTitle ? ($activeTitle->data['shadow'] ?? 'none') : 'none';
        $titleAnimated  = $activeTitle ? ($activeTitle->data['animated'] ?? false) : false;
    @endphp

    <div class="min-h-full">

        {{-- ── Hero Header ──────────────────────────────────────────── --}}
        <div class="page-hero px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <img src="{{ asset('images/banniere5.jpg') }}" alt="" class="page-hero-bg" />
            <div class="page-hero-overlay"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-6">
                    {{-- Avatar with frame --}}
                    <div class="w-24 h-24 rounded-2xl overflow-hidden flex items-center justify-center flex-shrink-0"
                         style="border: {{ $frameBorder }}; box-shadow: {{ $frameShadow }}; background: var(--bg-card);">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="Avatar" class="w-20 h-20 rounded-xl object-cover" />
                        @else
                            <div class="w-20 h-20 rounded-xl flex items-center justify-center text-3xl font-black" style="background: var(--accent); color: var(--text-inverse);">
                                {{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-black tracking-tight" style="color: var(--text-primary);">{{ $user->name }}</h1>
                        @if($activeTitle)
                            <p class="text-sm font-extrabold mt-1 {{ $titleAnimated ? 'title-animated' : '' }}"
                               style="background: {{ $titleGradient }}; background-size: {{ $titleAnimated ? '200% 200%' : 'auto' }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: {{ $titleShadow }};">
                                {{ $activeTitle->name }}
                            </p>
                        @endif
                        <p class="text-xs mt-2" style="color: var(--text-muted);">Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                {{-- Stats bar --}}
                <div class="flex items-center gap-6 mt-6">
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                         style="background: rgba(212,168,67,0.1); border: 1px solid rgba(212,168,67,0.25);">
                        <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-5 h-5 object-contain" />
                        <span class="text-lg font-extrabold" style="color: #d4a843;">{{ number_format($user->poketokens, 0, ',', ' ') }}</span>
                        <span class="text-xs font-medium" style="color: var(--text-muted);">PokéTokens</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1);">
                        <svg class="w-5 h-5" style="color: rgba(255,255,255,0.7);" fill="currentColor" viewBox="0 0 576 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>
                        <span class="text-lg font-extrabold" style="color: var(--text-primary);">{{ $cardsCount }}</span>
                        <span class="text-xs font-medium" style="color: var(--text-muted);">Cartes</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-10 py-10">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- ═══ LEFT COLUMN — Cosmetics ═══════════════════════════ --}}
                <div class="xl:col-span-2 space-y-6">

                    {{-- ── Active Cosmetics Overview ────────────────────── --}}
                    <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-base font-bold" style="color: var(--text-primary);">Cosmetiques equipes</h2>
                            <a href="{{ route('shop') }}" class="btn btn-surface btn-sm">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 576 512"><path d="M253.3 35.1c6.1-11.8 1.5-26.3-10.2-32.4s-26.3-1.5-32.4 10.2L117.6 192 32 192c-17.7 0-32 14.3-32 32s14.3 32 32 32L0 448c0 17.7 14.3 32 32 32H576c17.7 0 32-14.3 32-32l-32-192c17.7 0 32-14.3 32-32s-14.3-32-32-32H490.4L397.3 12.9C391.2 1.2 376.7-3.4 365 2.7s-16.3 20.6-10.2 32.4L430.4 192H177.6L253.3 35.1z"/></svg>
                                Boutique
                            </a>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Title --}}
                            <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="w-10 h-10 rounded-lg mx-auto mb-3 flex items-center justify-center"
                                     style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    <svg class="w-5 h-5" style="color: rgba(255,255,255,0.85);" fill="currentColor" viewBox="0 0 576 512"><path d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6H426.6c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>
                                </div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color: var(--text-muted);">Titre</p>
                                @if($activeTitle)
                                    <p class="text-xs font-bold {{ $titleAnimated ? 'title-animated' : '' }}"
                                       style="background: {{ $titleGradient }}; background-size: {{ $titleAnimated ? '200% 200%' : 'auto' }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: {{ $titleShadow }};">
                                        {{ $activeTitle->name }}
                                    </p>
                                    <form action="{{ route('shop.unequip', 'title') }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-medium cursor-pointer" style="color: var(--text-muted);">Retirer</button>
                                    </form>
                                @else
                                    <p class="text-xs" style="color: var(--text-muted);">Aucun</p>
                                @endif
                            </div>

                            {{-- Frame --}}
                            <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="w-10 h-10 rounded-lg mx-auto mb-3 flex items-center justify-center"
                                     style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    <svg class="w-5 h-5" style="color: rgba(255,255,255,0.85);" fill="currentColor" viewBox="0 0 448 512"><path d="M0 96C0 60.7 28.7 32 64 32H384c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM48 368v48c0 8.8 7.2 16 16 16h48V368H48zm80 64H320V368H128v64zm224 0h48c8.8 0 16-7.2 16-16V368H352v64zM448 144c0-8.8-7.2-16-16-16H352v64h96V144zM320 128H128v64H320V128zM112 128H64c-8.8 0-16 7.2-16 16v48h64V128zM48 240v80H112V240H48zm80 80H320V240H128v80zm224-80v80h96V240H352z"/></svg>
                                </div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color: var(--text-muted);">Cadre</p>
                                @if($activeFrame)
                                    <div class="w-10 h-10 rounded-lg mx-auto mb-1" style="border: {{ $frameBorder }}; box-shadow: {{ $frameShadow }}; background: var(--bg-card);"></div>
                                    <p class="text-xs font-bold" style="color: var(--text-primary);">{{ $activeFrame->name }}</p>
                                    <form action="{{ route('shop.unequip', 'frame') }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-medium cursor-pointer" style="color: var(--text-muted);">Retirer</button>
                                    </form>
                                @else
                                    <p class="text-xs" style="color: var(--text-muted);">Aucun</p>
                                @endif
                            </div>

                            {{-- Sleeve --}}
                            <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="w-10 h-10 rounded-lg mx-auto mb-3 flex items-center justify-center"
                                     style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    <svg class="w-5 h-5" style="color: rgba(255,255,255,0.85);" fill="currentColor" viewBox="0 0 576 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>
                                </div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color: var(--text-muted);">Sleeve</p>
                                @if($activeSleeve)
                                    @if(isset($activeSleeve->data['image']))
                                        <div class="w-10 h-14 rounded mx-auto mb-1 overflow-hidden" style="border: 1px solid var(--border);">
                                            <img src="{{ asset($activeSleeve->data['image']) }}" alt="" class="w-full h-full object-cover" />
                                        </div>
                                    @endif
                                    <p class="text-xs font-bold" style="color: var(--text-primary);">{{ $activeSleeve->name }}</p>
                                    <form action="{{ route('shop.unequip', 'sleeve') }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-medium cursor-pointer" style="color: var(--text-muted);">Retirer</button>
                                    </form>
                                @else
                                    <p class="text-xs" style="color: var(--text-muted);">Aucune</p>
                                @endif
                            </div>

                            {{-- Avatar --}}
                            <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="w-10 h-10 rounded-lg mx-auto mb-3 flex items-center justify-center"
                                     style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    <svg class="w-5 h-5" style="color: rgba(255,255,255,0.85);" fill="currentColor" viewBox="0 0 512 512"><path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                                </div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color: var(--text-muted);">Avatar</p>
                                @if($user->avatar)
                                    <div class="w-10 h-10 rounded-full mx-auto mb-1 overflow-hidden" style="border: 2px solid var(--border);">
                                        <img src="{{ $user->avatar }}" alt="" class="w-full h-full object-cover" />
                                    </div>
                                    <p class="text-xs font-bold" style="color: var(--text-primary);">Personnalise</p>
                                    <form action="{{ route('shop.unequip', 'avatar') }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-medium cursor-pointer" style="color: var(--text-muted);">Retirer</button>
                                    </form>
                                @else
                                    <p class="text-xs" style="color: var(--text-muted);">Par defaut</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Quick Equip: Owned Titles ────────────────────── --}}
                    @if(($ownedItems['title'] ?? collect())->count() > 0)
                        <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                            <h3 class="text-sm font-bold mb-4" style="color: var(--text-primary);">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color: #ffd700;" fill="currentColor" viewBox="0 0 576 512"><path d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6H426.6c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>
                                    Mes Titres
                                    <span class="list-pill">{{ ($ownedItems['title'] ?? collect())->count() }}</span>
                                </span>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($ownedItems['title'] ?? [] as $item)
                                    @php
                                        $isActive = $user->active_title === $item->slug;
                                        $grad = $item->data['gradient'] ?? 'linear-gradient(135deg, #9CA3AF, #6B7280)';
                                        $shad = $item->data['shadow'] ?? 'none';
                                        $anim = $item->data['animated'] ?? false;
                                    @endphp
                                    <form action="{{ $isActive ? route('shop.unequip', 'title') : route('shop.equip', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $anim ? 'title-animated' : '' }}"
                                                style="background: {{ $isActive ? 'rgba(34,197,94,0.1)' : 'var(--bg-card)' }}; border: 1px solid {{ $isActive ? 'rgba(34,197,94,0.3)' : 'var(--border)' }}; {{ $isActive ? '' : 'color: var(--text-secondary);' }}">
                                            <span style="background: {{ $grad }}; background-size: {{ $anim ? '200% 200%' : 'auto' }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: {{ $shad }};">
                                                {{ $item->name }}
                                            </span>
                                            @if($isActive)
                                                <svg class="w-3 h-3 inline-block ml-1" style="color: #22c55e; -webkit-text-fill-color: #22c55e;" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── Quick Equip: Owned Sleeves ──────────────────── --}}
                    @if(($ownedItems['sleeve'] ?? collect())->count() > 0)
                        <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                            <h3 class="text-sm font-bold mb-4" style="color: var(--text-primary);">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color: #ef4444;" fill="currentColor" viewBox="0 0 576 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>
                                    Mes Sleeves
                                    <span class="list-pill">{{ ($ownedItems['sleeve'] ?? collect())->count() }}</span>
                                </span>
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($ownedItems['sleeve'] ?? [] as $item)
                                    @php
                                        $isActive  = $user->active_sleeve === $item->slug;
                                        $slvImg    = $item->data['image'] ?? null;
                                        $isExcl    = $item->data['exclusive'] ?? false;
                                    @endphp
                                    <form action="{{ $isActive ? route('shop.unequip', 'sleeve') : route('shop.equip', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="relative w-14 h-20 rounded-lg overflow-hidden transition-all cursor-pointer group"
                                                style="border: 2px solid {{ $isActive ? '#22c55e' : ($isExcl ? 'rgba(255,215,0,0.5)' : 'var(--border)') }}; box-shadow: {{ $isActive ? '0 0 10px rgba(34,197,94,0.3)' : ($isExcl ? '0 0 10px rgba(255,215,0,0.15)' : 'none') }};"
                                                title="{{ $item->name }}">
                                            @if($slvImg)
                                                <img src="{{ asset($slvImg) }}" alt="{{ $item->name }}" class="w-full h-full object-cover" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center" style="background: var(--bg-card);">
                                                    <svg class="w-5 h-5" style="color: var(--text-muted);" fill="currentColor" viewBox="0 0 512 512"><path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0z"/></svg>
                                                </div>
                                            @endif
                                            @if($isActive)
                                                <div class="absolute bottom-0 inset-x-0 py-0.5 text-center text-[8px] font-bold" style="background: rgba(34,197,94,0.85); color: white;">Actif</div>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── Quick Equip: Owned Frames ──────────────────── --}}
                    @if(($ownedItems['frame'] ?? collect())->count() > 0)
                        <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                            <h3 class="text-sm font-bold mb-4" style="color: var(--text-primary);">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color: #a382ff;" fill="currentColor" viewBox="0 0 448 512"><path d="M0 96C0 60.7 28.7 32 64 32H384c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96z"/></svg>
                                    Mes Cadres
                                    <span class="list-pill">{{ ($ownedItems['frame'] ?? collect())->count() }}</span>
                                </span>
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($ownedItems['frame'] ?? [] as $item)
                                    @php
                                        $isActive   = $user->active_frame === $item->slug;
                                        $bdr = $item->data['border'] ?? '2px solid var(--border)';
                                        $shd = $item->data['shadow'] ?? 'none';
                                    @endphp
                                    <form action="{{ $isActive ? route('shop.unequip', 'frame') : route('shop.equip', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="relative w-14 h-14 rounded-xl transition-all cursor-pointer flex items-center justify-center"
                                                style="border: {{ $bdr }}; box-shadow: {{ $shd }}; background: var(--bg-card); {{ $isActive ? 'outline: 2px solid #22c55e; outline-offset: 2px;' : '' }}"
                                                title="{{ $item->name }}">
                                            <span class="text-xs font-bold" style="color: var(--text-secondary);">{{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}</span>
                                            @if($isActive)
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center" style="background: #22c55e;">
                                                    <svg class="w-2.5 h-2.5" fill="white" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                                </div>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── Quick Equip: Owned Avatars ──────────────────── --}}
                    @if(($ownedItems['avatar'] ?? collect())->count() > 0)
                        <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                            <h3 class="text-sm font-bold mb-4" style="color: var(--text-primary);">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color: #22c55e;" fill="currentColor" viewBox="0 0 512 512"><path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                                    Mes Avatars
                                    <span class="list-pill">{{ ($ownedItems['avatar'] ?? collect())->count() }}</span>
                                </span>
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($ownedItems['avatar'] ?? [] as $item)
                                    @php
                                        $avImg = $item->data['image'] ?? '';
                                        $isActive  = $user->avatar && str_contains($user->avatar, basename($avImg));
                                        $isExcl    = $item->data['exclusive'] ?? false;
                                    @endphp
                                    <form action="{{ route('shop.equip', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="relative w-14 h-14 rounded-full overflow-hidden transition-all cursor-pointer"
                                                style="border: 2px solid {{ $isActive ? '#22c55e' : ($isExcl ? 'rgba(255,215,0,0.5)' : 'var(--border)') }}; box-shadow: {{ $isActive ? '0 0 10px rgba(34,197,94,0.3)' : ($isExcl ? '0 0 8px rgba(255,215,0,0.15)' : 'none') }};"
                                                title="{{ $item->name }}">
                                            <img src="{{ asset($avImg) }}" alt="{{ $item->name }}" class="w-full h-full object-cover" />
                                            @if($isActive)
                                                <div class="absolute inset-0 flex items-center justify-center" style="background: rgba(34,197,94,0.3);">
                                                    <svg class="w-5 h-5" fill="white" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                                </div>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ═══ RIGHT COLUMN — Account Settings ═══════════════════ --}}
                <div class="space-y-6">

                    {{-- ── Update profile ───────────────────────────────── --}}
                    <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                        <h2 class="text-base font-bold mb-1" style="color: var(--text-primary);">Informations</h2>
                        <p class="text-xs mb-5" style="color: var(--text-muted);">Modifie ton nom de dresseur et ton email.</p>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="mb-4">
                                <label for="name" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary);">Nom de dresseur</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="input-dark" />
                                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-400" />
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary);">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="input-dark" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit" class="btn btn-primary btn-sm">Sauvegarder</button>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-xs text-green-400 font-medium">Sauvegarde.</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- ── Update password ──────────────────────────────── --}}
                    <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                        <h2 class="text-base font-bold mb-1" style="color: var(--text-primary);">Mot de passe</h2>
                        <p class="text-xs mb-5" style="color: var(--text-muted);">Utilise un mot de passe long et unique.</p>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-4">
                                <label for="current_password" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary);">Mot de passe actuel</label>
                                <input id="current_password" type="password" name="current_password"
                                       class="input-dark" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-red-400" />
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary);">Nouveau mot de passe</label>
                                <input id="password" type="password" name="password"
                                       class="input-dark" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-red-400" />
                            </div>

                            <div class="mb-5">
                                <label for="password_confirmation" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary);">Confirmer</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       class="input-dark" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-red-400" />
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">Changer le mot de passe</button>
                        </form>
                    </div>

                    {{-- ── Delete account ───────────────────────────────── --}}
                    <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid rgba(239,68,68,0.15);">
                        <h2 class="text-base font-bold text-red-400 mb-1">Zone dangereuse</h2>
                        <p class="text-xs mb-5" style="color: var(--text-muted);">La suppression du compte est irreversible.</p>

                        <form method="POST" action="{{ route('profile.destroy') }}"
                              x-data="{ confirm: false }"
                              @submit.prevent="if(confirm) $el.submit(); else confirm = true;">
                            @csrf
                            @method('delete')

                            <div x-show="confirm" class="mb-4">
                                <label for="delete_password" class="block text-xs font-semibold text-red-300 mb-1.5">Confirme avec ton mot de passe</label>
                                <input id="delete_password" type="password" name="password"
                                       class="input-dark" style="border-color: rgba(239,68,68,0.3);" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" />
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-red-400" />
                            </div>

                            <button type="submit" class="btn btn-sm" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2);">
                                <span x-text="confirm ? 'Confirmer la suppression' : 'Supprimer mon compte'"></span>
                            </button>
                            <button type="button" x-show="confirm" @click="confirm = false" class="btn btn-ghost btn-sm ml-2">Annuler</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
