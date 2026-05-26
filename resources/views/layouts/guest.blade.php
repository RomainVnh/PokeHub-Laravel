<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PokeHub') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            const t = localStorage.getItem('pokehub_theme') || 'dark';
            if (t === 'light') document.documentElement.setAttribute('data-theme', 'light');
        })();
    </script>
</head>
<body style="font-family: 'Inter', sans-serif; background: var(--bg-base); margin: 0; padding: 0; height: 100%; overflow: hidden;">

    <div style="display: flex; height: 100vh;">

        {{-- ═══ LEFT — Form panel ════════════════════════════════════ --}}
        <div class="w-[480px] flex-shrink-0 h-full flex flex-col overflow-y-auto"
             style="background: var(--bg-primary); border-right: 1px solid var(--border);">

            {{-- Content wrapper —  vertically centered --}}
            <div class="flex-1 flex flex-col justify-center px-12 py-10">

                {{-- Logo --}}
                <div class="mb-10">
                    <a href="/">
                        <img src="{{ asset('images/logopokemonhub.png') }}" alt="PokemonHub" style="height: 160px; object-fit: contain;" />
                    </a>
                </div>

                {{-- Form slot --}}
                {{ $slot }}

                {{-- Footer --}}
                <div class="mt-10 pt-6" style="border-top: 1px solid var(--border);">
                    <p class="text-[11px]" style="color: var(--text-muted);">
                        Copyright &copy; {{ date('Y') }} PokeHub. Tous droits reserv&eacute;s.
                    </p>
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT — Image panel ══════════════════════════════════ --}}
        <div class="flex-1 relative overflow-hidden">
            <img src="{{ asset('images/' . ($banner ?? 'banniere2.jpg')) }}" alt=""
                 style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center center; display: block;" />

            {{-- Subtle dark edges --}}
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(10,12,16,0.15) 0%, transparent 40%, rgba(10,12,16,0.1) 100%);"></div>

            {{-- Bottom stats card --}}
            <div class="absolute bottom-8 right-8 max-w-xs w-full rounded-2xl p-5"
                 style="background: rgba(0,0,0,0.45); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.1);">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.1);">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 576 512">
                            <path d="M290.8 48.6l78.4 29.7L288 109.5 206.8 78.3l78.4-29.7c1.8-.7 3.8-.7 5.7 0zM136 92.5V204.7c-1.3 .4-2.6 .8-3.9 1.3l-96 36.4C14.4 250.6 0 271.5 0 294.7V413.9c0 22.2 13.1 42.3 33.5 51.3l96 42.2c14.4 6.3 30.7 6.3 45.1 0L288 461.8l113.5 45.6c14.4 6.3 30.7 6.3 45.1 0l96-42.2c20.3-8.9 33.5-29.1 33.5-51.3V294.7c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-1.3-.5-2.6-.9-3.9-1.3V92.5c0-23.3-14.4-44.1-36.1-52.4l-96-36.4c-12.2-4.6-25.8-4.6-38 0l-96 36.4C150.4 48.4 136 69.3 136 92.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-white">Boosters disponibles</p>
                        <p class="text-[11px]" style="color: rgba(255,255,255,0.5);">Toutes les &eacute;ditions</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-2xl font-black text-white">173+</p>
                        <p class="text-[10px]" style="color: rgba(255,255,255,0.5);">&Eacute;ditions</p>
                    </div>
                    <div class="w-px h-8" style="background: rgba(255,255,255,0.15);"></div>
                    <div>
                        <p class="text-2xl font-black text-white">10k+</p>
                        <p class="text-[10px]" style="color: rgba(255,255,255,0.5);">Cartes uniques</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
