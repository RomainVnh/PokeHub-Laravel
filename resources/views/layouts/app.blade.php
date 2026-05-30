<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PokeHub' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoreduit.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        (function() {
            const t = localStorage.getItem('pokehub_theme') || 'dark';
            if (t === 'light') document.documentElement.setAttribute('data-theme', 'light');
        })();
    </script>
</head>
<body class="h-full overflow-hidden" style="font-family: 'Inter', sans-serif;"
      x-data="{
          mobileMenu: false,
          collapsed: false,
          forceSidebar: localStorage.getItem('pokehub_force_sidebar') === 'true',
          settingsOpen: false,
          toggleForceSidebar() {
              this.forceSidebar = !this.forceSidebar;
              localStorage.setItem('pokehub_force_sidebar', this.forceSidebar);
          }
      }">

    <div class="h-full flex">

    {{-- ═══ Mobile top bar (burger) ════════════════════════════════════ --}}
    <header class="mobile-topbar" :class="forceSidebar ? 'hidden' : ''"
            style="background: var(--bg-primary); border-bottom: 1px solid var(--border);">
        <button @click="mobileMenu = !mobileMenu" class="burger-btn" aria-label="Menu">
            <span class="burger-line" :class="mobileMenu && 'open'"></span>
            <span class="burger-line" :class="mobileMenu && 'open'"></span>
            <span class="burger-line" :class="mobileMenu && 'open'"></span>
        </button>
        <a href="/" class="flex items-center">
            <img src="{{ asset('images/logopokehub_2.png') }}" alt="PokeHub" class="h-8 object-contain" />
        </a>
        <div class="w-10"></div>
    </header>

    {{-- ═══ Mobile overlay ═════════════════════════════════════════════ --}}
    <div x-show="mobileMenu" x-transition.opacity.duration.200ms
         @click="mobileMenu = false"
         class="mobile-overlay" :class="forceSidebar ? 'hidden' : ''"></div>

    {{-- ═══ Sidebar ═══════════════════════════════════════════════════ --}}
    <aside
        :class="[
            collapsed ? 'sidebar-collapsed' : 'sidebar-expanded',
            mobileMenu ? 'mobile-sidebar-open' : '',
            forceSidebar ? 'sidebar-force' : 'sidebar-responsive'
        ]"
        class="sidebar-main flex-shrink-0 h-full flex flex-col transition-all duration-300 overflow-hidden"
        style="background: var(--bg-primary); border-right: 1px solid var(--border);"
    >
        {{-- ── Header: logo + Menu label + collapse toggle ────────── --}}
        <div class="sidebar-header">
            <a href="/" class="sidebar-logo-link">
                <img x-show="!collapsed" x-transition.opacity
                     src="{{ asset('images/logopokehub_2.png') }}"
                     alt="PokeHub" class="sidebar-logo-full" />
                <img x-show="collapsed"
                     src="{{ asset('images/logoreduit_transparent.png') }}"
                     alt="PokeHub" class="sidebar-logo-icon" />
            </a>
            <button @click="collapsed = !collapsed"
                    class="sidebar-toggle-btn" title="Réduire / agrandir">
                <svg x-show="!collapsed" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                <svg x-show="collapsed" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        {{-- ── Navigation ─────────────────────────────────────────── --}}
        <nav class="sidebar-nav">
            @php
                $navItems = [
                    ['href' => '/',             'label' => 'Accueil',       'vb' => '576', 'icon' => 'M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V360c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32V472c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z'],
                    ['href' => '/encyclopedia', 'label' => 'Encyclopédie',  'vb' => '448', 'icon' => 'M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z'],
                    ['href' => '/open',         'label' => 'Boosters',      'vb' => '576', 'icon' => 'M290.8 48.6l78.4 29.7L288 109.5 206.8 78.3l78.4-29.7c1.8-.7 3.8-.7 5.7 0zM136 92.5V204.7c-1.3 .4-2.6 .8-3.9 1.3l-96 36.4C14.4 250.6 0 271.5 0 294.7V413.9c0 22.2 13.1 42.3 33.5 51.3l96 42.2c14.4 6.3 30.7 6.3 45.1 0L288 461.8l113.5 45.6c14.4 6.3 30.7 6.3 45.1 0l96-42.2c20.3-8.9 33.5-29.1 33.5-51.3V294.7c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-1.3-.5-2.6-.9-3.9-1.3V92.5c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-12.2-4.6-25.8-4.6-38 0l-96 36.4C150.4 48.4 136 69.3 136 92.5z'],
                    ['href' => '/friends',      'label' => 'Amis',          'vb' => '640', 'icon' => 'M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96H21.3C9.6 320 0 310.4 0 298.7zM405.3 320h-.7c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM416 224a112 112 0 1 0 -224 0 112 112 0 1 0 224 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z'],
                    ['href' => '/trades',       'label' => 'Échanges',      'vb' => '512', 'icon' => 'M32 96l320 0V32c0-12.9 7.8-24.6 19.8-29.6s25.7-2.2 34.9 6.9l128 128c12.5 12.5 12.5 32.8 0 45.3l-128 128c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V224L32 224c-17.7 0-32-14.3-32-32V128c0-17.7 14.3-32 32-32zM480 416H160V480c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6v64H480c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32z'],
                    ['href' => '/prices',       'label' => 'Prix',          'vb' => '576', 'icon' => 'M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z'],
                ];
                if (Auth::check()) {
                    $navItems[] = ['href' => '/shop', 'label' => 'Boutique', 'vb' => '576', 'icon' => 'M253.3 35.1c6.1-11.8 1.5-26.3-10.2-32.4s-26.3-1.5-32.4 10.2L117.6 192 32 192c-17.7 0-32 14.3-32 32s14.3 32 32 32L0 448c0 17.7 14.3 32 32 32H576c17.7 0 32-14.3 32-32l-32-192c17.7 0 32-14.3 32-32s-14.3-32-32-32H490.4L397.3 12.9C391.2 1.2 376.7-3.4 365 2.7s-16.3 20.6-10.2 32.4L430.4 192H177.6L253.3 35.1zM176 288v96c0 13.3-10.7 24-24 24s-24-10.7-24-24V288c0-13.3 10.7-24 24-24s24 10.7 24 24zm112 0v96c0 13.3-10.7 24-24 24s-24-10.7-24-24V288c0-13.3 10.7-24 24-24s24 10.7 24 24zm112 0v96c0 13.3-10.7 24-24 24s-24-10.7-24-24V288c0-13.3 10.7-24 24-24s24 10.7 24 24z'];
                }
                $currentPath = '/'.ltrim(request()->path(), '/');
            @endphp

            @foreach($navItems as $item)
                @php
                    $active = ($item['href'] === '/' && $currentPath === '/') || ($item['href'] !== '/' && str_starts_with($currentPath, $item['href']));
                @endphp
                <a href="{{ $item['href'] }}" class="sidebar-item {{ $active ? 'active' : '' }}" @click="mobileMenu = false"
                   :title="collapsed ? '{{ $item['label'] }}' : ''">
                    <span class="sidebar-item-icon">
                        <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 {{ $item['vb'] }} 512">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                    </span>
                    <span x-show="!collapsed" x-transition.opacity class="sidebar-item-label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- ── Bottom section ─────────────────────────────────────── --}}
        <div class="sidebar-footer">
            {{-- Token display --}}
            @auth
                <a href="/shop" class="sidebar-token-pill" x-show="!collapsed" @click="mobileMenu = false">
                    <div class="sidebar-token-icon">
                        <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-full h-full object-contain" />
                    </div>
                    <span class="sidebar-token-amount">{{ number_format(auth()->user()->poketokens, 0, ',', ' ') }}</span>
                </a>
                <div x-show="collapsed" class="flex justify-center">
                    <a href="/shop" class="sidebar-item-icon" title="{{ number_format(auth()->user()->poketokens, 0, ',', ' ') }} PokéTokens" style="color: #d4a843;" @click="mobileMenu = false">
                        <img src="{{ asset('images/poketoken.png') }}" alt="PokéToken" class="w-4 h-4 object-contain" />
                    </a>
                </div>
            @endauth

            @auth
                @php
                    $activeFrame = null;
                    if (auth()->user()->active_frame) {
                        $activeFrame = \App\Models\ShopItem::where('slug', auth()->user()->active_frame)->first();
                    }
                    $activeTitle = null;
                    if (auth()->user()->active_title) {
                        $activeTitle = \App\Models\ShopItem::where('slug', auth()->user()->active_title)->first();
                    }
                @endphp

                {{-- Expanded: profile card --}}
                <div x-show="!collapsed" class="sidebar-profile-card">
                    <a href="{{ route('profile.edit') }}" class="sidebar-profile-link" @click="mobileMenu = false">
                        <div class="sidebar-profile-avatar"
                             style="{{ $activeFrame ? 'border: ' . ($activeFrame->data['border'] ?? '') . '; box-shadow: ' . ($activeFrame->data['shadow'] ?? 'none') . ';' : '' }}">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-full h-full object-cover" />
                            @else
                                <span class="text-xs font-bold" style="color: var(--text-inverse); background: var(--accent); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="sidebar-profile-info">
                            <p class="sidebar-profile-name">{{ auth()->user()->name ?? 'Dresseur' }}</p>
                            @if($activeTitle)
                                <p class="sidebar-profile-title {{ ($activeTitle->data['animated'] ?? false) ? 'title-animated' : '' }}" style="background: {{ $activeTitle->data['gradient'] ?? '' }}; background-size: {{ ($activeTitle->data['animated'] ?? false) ? '200% 200%' : 'auto' }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: {{ $activeTitle->data['shadow'] ?? 'drop-shadow(0 1px 2px rgba(0,0,0,0.3))' }};">{{ $activeTitle->name }}</p>
                            @else
                                <p class="sidebar-profile-title" style="color: var(--text-muted);">Mon profil</p>
                            @endif
                        </div>
                    </a>
                    <div class="sidebar-profile-actions">
                        <button @click="settingsOpen = !settingsOpen"
                                class="sidebar-action-btn" title="Paramètres">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-action-btn" title="Déconnexion">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                {{-- Collapsed: avatar only --}}
                <div x-show="collapsed" class="flex justify-center">
                    <a href="{{ route('profile.edit') }}" class="sidebar-avatar-collapsed" title="{{ auth()->user()->name ?? 'Mon profil' }}">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-full h-full object-cover rounded-full" />
                        @else
                            <span class="text-[11px] font-bold" style="color: var(--text-inverse);">{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}</span>
                        @endif
                    </a>
                </div>
            @else
                <a href="{{ route('login') }}" class="sidebar-item" @click="mobileMenu = false"
                   :title="collapsed ? 'Connexion' : ''">
                    <span class="sidebar-item-icon">
                        <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 512 512">
                            <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/>
                        </svg>
                    </span>
                    <span x-show="!collapsed" x-transition.opacity class="sidebar-item-label font-semibold">Connexion</span>
                </a>
            @endauth

            {{-- Theme toggle --}}
            <div class="sidebar-theme-row">
                <div class="sidebar-theme-toggle"
                     @click="(() => {
                        const html = document.documentElement;
                        const isLight = html.getAttribute('data-theme') === 'light';
                        if (isLight) { html.removeAttribute('data-theme'); localStorage.setItem('pokehub_theme','dark'); }
                        else { html.setAttribute('data-theme','light'); localStorage.setItem('pokehub_theme','light'); }
                     })()">
                    <div class="theme-toggle" :style="collapsed ? 'width:32px;height:18px' : ''" title="Changer de thème"></div>
                    <span x-show="!collapsed" class="text-[11px] font-medium" style="color: var(--text-muted);">Thème</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══ Main ══════════════════════════════════════════════════════ --}}
    <main class="flex-1 overflow-y-auto main-content relative">
        {{-- ── XP / Level bar (top-right) ─────────────────────────────── --}}
        @auth
            @php
                $xpUser = auth()->user();
                $xpNeeded = $xpUser->xpToNextLevel();
                $xpProgress = $xpUser->xpProgress();
                $xpActiveFrame = null;
                if ($xpUser->active_frame) {
                    $xpActiveFrame = \App\Models\ShopItem::where('slug', $xpUser->active_frame)->first();
                }
                $xpActiveTitle = null;
                if ($xpUser->active_title) {
                    $xpActiveTitle = \App\Models\ShopItem::where('slug', $xpUser->active_title)->first();
                }
            @endphp
            <div class="xp-bar-floating">
                <a href="{{ route('profile.edit') }}" class="xp-bar-avatar" style="{{ $xpActiveFrame ? 'border: ' . ($xpActiveFrame->data['border'] ?? '2px solid var(--border)') . '; box-shadow: ' . ($xpActiveFrame->data['shadow'] ?? 'none') . ';' : '' }}">
                    @if($xpUser->avatar)
                        <img src="{{ $xpUser->avatar }}" alt="" class="w-full h-full object-cover" />
                    @else
                        <span class="text-xs font-bold" style="color: var(--text-primary);">{{ strtoupper(substr($xpUser->name ?? 'D', 0, 1)) }}</span>
                    @endif
                </a>
                <div class="xp-bar-info">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold truncate" style="color: var(--text-primary); max-width: 100px;">{{ $xpUser->name }}</span>
                        @if($xpActiveTitle)
                            <span class="text-[9px] font-extrabold truncate {{ ($xpActiveTitle->data['animated'] ?? false) ? 'title-animated' : '' }}"
                                  style="background: {{ $xpActiveTitle->data['gradient'] ?? '' }}; background-size: {{ ($xpActiveTitle->data['animated'] ?? false) ? '200% 200%' : 'auto' }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: {{ $xpActiveTitle->data['shadow'] ?? 'none' }}; max-width: 80px;">
                                {{ $xpActiveTitle->name }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="xp-bar-track">
                            <div class="xp-bar-fill" style="width: {{ $xpProgress }}%;"></div>
                        </div>
                        <span class="text-[9px] font-bold whitespace-nowrap" style="color: var(--text-muted);">{{ $xpUser->xp }}/{{ $xpNeeded }}</span>
                    </div>
                </div>
                <div class="xp-bar-level">
                    <span class="text-sm font-black">{{ $xpUser->level }}</span>
                </div>
            </div>
        @endauth

        {{ $slot }}
    </main>

    </div>

    {{-- ═══ Settings modal ═════════════════════════════════════════════ --}}
    <div x-show="settingsOpen" x-transition.opacity
         @click.self="settingsOpen = false" @keydown.escape.window="settingsOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);">
        <div x-show="settingsOpen" x-transition.scale.origin.center
             class="relative w-full max-w-sm mx-4 rounded-2xl overflow-hidden"
             style="background: var(--bg-card); border: 1px solid var(--border);">
            <div class="p-6">
                <h3 class="text-lg font-extrabold mb-5" style="color: var(--text-primary);">Paramètres</h3>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl" style="background: var(--bg-surface); border: 1px solid var(--border);">
                        <p class="text-xs font-bold mb-1" style="color: var(--text-muted);">Accessibilité</p>
                        <div class="flex items-center justify-between">
                            <div class="flex-1 pr-4">
                                <p class="text-sm font-semibold" style="color: var(--text-primary);">Barre latérale fixe</p>
                                <p class="text-[11px] mt-0.5" style="color: var(--text-muted);">Conserver la sidebar même sur mobile au lieu du menu burger</p>
                            </div>
                            <button @click="toggleForceSidebar()"
                                    class="relative flex-shrink-0 w-11 h-6 rounded-full transition-colors duration-200 cursor-pointer"
                                    :style="forceSidebar ? 'background: #22c55e' : 'background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15)'">
                                <span class="absolute top-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-white shadow transition-all duration-200"
                                      :style="forceSidebar ? 'left: calc(100% - 20px)' : 'left: 3px'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <button @click="settingsOpen = false"
                        class="btn btn-surface w-full mt-5">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ Toast notification ═══════════════════════════════════════ --}}
    @if(session('login_success') || session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity
             x-init="setTimeout(() => show = false, 3500)"
             class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-2xl"
             style="background: linear-gradient(135deg, rgba(34,197,94,0.95), rgba(22,163,74,0.95)); color: white; backdrop-filter: blur(12px);">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 512 512">
                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
            </svg>
            <span class="text-sm font-bold">{{ session('login_success') ?? session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity
             x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-2xl"
             style="background: linear-gradient(135deg, rgba(239,68,68,0.95), rgba(220,38,38,0.95)); color: white; backdrop-filter: blur(12px);">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 512 512">
                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/>
            </svg>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

</body>
</html>
