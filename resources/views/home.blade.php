<x-app-layout>
    <x-slot:title>PokeHub — Explore & Collecte</x-slot:title>

    <div class="min-h-full">

        {{-- ═══ Hero ══════════════════════════════════════════════════ --}}
        <section class="relative overflow-hidden" style="height: 520px;">
            <img src="{{ asset('images/banniere.jpg') }}" alt=""
                 class="absolute inset-0 w-full h-full object-cover page-hero-bg"
                 style="opacity: 0.25; filter: blur(2px) saturate(1.2);" />
            <div class="absolute inset-0 page-hero-overlay"
                 style="background: linear-gradient(180deg, rgba(10,12,16,0.4) 0%, rgba(10,12,16,0.7) 50%, var(--bg-base) 100%);"></div>

            <div class="relative h-full flex flex-col items-center justify-center text-center px-8">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full mb-8"
                     style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                    <span class="w-2 h-2 rounded-full animate-pulse" style="background: var(--green);"></span>
                    <span class="text-sm font-medium text-white">{{ $totalSets }} éditions disponibles</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-black text-white mb-5 leading-[1.1] tracking-tight">
                    Explore et collecte<br>
                    <span class="text-white">chaque carte Pokémon</span>
                </h1>

                <p class="text-lg leading-relaxed mb-10 max-w-lg" style="color: var(--text-secondary);">
                    Ouvre des boosters, découvre des cartes rares et constitue ta collection complète. Toutes les éditions depuis 1999.
                </p>

                {{-- Search bar --}}
                <div class="relative max-w-2xl mx-auto mb-8">
                    <svg class="absolute w-6 h-6 pointer-events-none" style="left: 22px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" fill="currentColor" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                    <form action="{{ route('encyclopedia') }}" method="GET">
                        <input type="text" name="q" placeholder="Rechercher une édition, un Pokemon..."
                               class="input-dark w-full text-lg" style="padding: 18px 24px 18px 58px; border-radius: 16px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(8px);" />
                    </form>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('encyclopedia') }}" class="btn btn-surface">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 448 512"><path d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
                        Encyclopédie
                    </a>
                    <a href="{{ route('open.index') }}" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M234.5 5.7c13.9-5.3 29.7-5.3 43.1 0l192 73.7C495.2 89.6 512 114.2 512 141.8V256 370.2c0 27.6-16.8 52.2-42.4 62.4l-192 73.7c-13.9 5.3-29.7 5.3-43.1 0l-192-73.7C16.8 422.4 0 397.8 0 370.2V256 141.8c0-27.6 16.8-52.2 42.4-62.4l192-73.7zM256 66.2L96.8 128 256 189.8 415.2 128 256 66.2zM272 434.8l160-61.5V199.5L272 261v173.8z"/></svg>
                        Ouvrir un booster
                    </a>
                </div>
            </div>
        </section>

        {{-- ═══ Stats ═════════════════════════════════════════════════ --}}
        <section class="py-14 px-8" style="border-bottom: 1px solid var(--border);">
            <div class="max-w-5xl mx-auto grid grid-cols-3 gap-6">
                @php
                    $stats = [
                        ['value' => $totalSets . '+', 'label' => 'Éditions disponibles', 'vb' => '448', 'icon' => 'M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z'],
                        ['value' => '10 000+',        'label' => 'Cartes uniques',       'vb' => '448', 'icon' => 'M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.4-20.8-29.6-20.8H272.5L349.4 44.6z'],
                        ['value' => '100%',           'label' => 'Gratuit pour tous',    'vb' => '576', 'icon' => 'M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329l104.2-103.1c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L388.2 150.3 316.9 18z'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="stat-card">
                        <svg class="w-6 h-6 mx-auto mb-3" style="color: white;" fill="currentColor" viewBox="0 0 {{ $stat['vb'] ?? '576' }} 512"><path d="{{ $stat['icon'] }}"/></svg>
                        <p class="text-3xl font-black mb-1 text-white">{{ $stat['value'] }}</p>
                        <p class="text-sm" style="color: var(--text-muted);">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ═══ Recent sets ══════════════════════════════════════════ --}}
        <section class="py-14 px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <span class="label-xs block mb-2" style="color: white;">Explorer</span>
                        <h2 class="text-2xl font-extrabold text-white">Dernières éditions</h2>
                    </div>
                    <a href="{{ route('encyclopedia') }}" class="flex items-center gap-2 text-sm font-semibold transition-colors"
                       style="color: white;"
                       onmouseover="this.style.color='rgba(255,255,255,0.7)'"
                       onmouseout="this.style.color='var(--gold)'">
                        Voir toutes
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 320 512"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                    @foreach($featured as $set)
                        <a href="{{ route('set.show', $set['id']) }}" class="edition-card group">
                            <div class="h-32 flex items-center justify-center p-5"
                                 style="background: linear-gradient(145deg, rgba(212,168,67,0.04) 0%, rgba(139,92,246,0.04) 100%);">
                                <img src="{{ $set['images']['logo'] ?? '' }}"
                                     alt="{{ $set['name'] }}"
                                     class="max-h-24 max-w-full object-contain group-hover:scale-110 transition-transform duration-300"
                                     onerror="this.style.opacity='0'" />
                            </div>
                            <div class="p-4 space-y-2">
                                <p class="text-sm font-bold text-white line-clamp-1">{{ $set['name'] }}</p>
                                <p class="text-[11px]" style="color: var(--text-muted);">
                                    {{ $set['series'] }} · {{ \Carbon\Carbon::parse($set['releaseDate'])->format('Y') }}
                                </p>
                                <div class="pt-1">
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full"
                                          style="color: white; background: var(--accent-bg);">
                                        {{ $set['printedTotal'] }} cartes
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ Actualités ══════════════════════════════════════════ --}}
        <section class="py-14 px-8" style="border-bottom: 1px solid var(--border);">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <span class="label-xs block mb-2" style="color: white;">Communauté</span>
                        <h2 class="text-2xl font-extrabold text-white">Actualités</h2>
                    </div>
                </div>

                @php
                    $articles = [
                        [
                            'image'    => asset('images/banniere2.webp'),
                            'tag'      => 'Nouvelle édition',
                            'tagColor' => 'var(--gold)',
                            'title'    => 'Destinées de Paldea 4.5 — Les cartes dévoilées',
                            'excerpt'  => 'La nouvelle extension promet des illustrations inédites et de nouvelles mécaniques ex. Découvrez les premières cartes révélées et les taux de drop estimés.',
                            'date'     => '24 mai 2026',
                            'author'   => 'Équipe PokeHub',
                            'icon'     => 'M234.5 5.7c13.9-5.3 29.7-5.3 43.1 0l192 73.7C495.2 89.6 512 114.2 512 141.8V370.2c0 27.6-16.8 52.2-42.4 62.4l-192 73.7c-13.9 5.3-29.7 5.3-43.1 0l-192-73.7C16.8 422.4 0 397.8 0 370.2V141.8c0-27.6 16.8-52.2 42.4-62.4l192-73.7z',
                            'iconVb'   => '512',
                        ],
                        [
                            'image'    => asset('images/boosters.jpg'),
                            'tag'      => 'Guide',
                            'tagColor' => 'var(--blue)',
                            'title'    => '5 astuces pour optimiser vos ouvertures de boosters',
                            'excerpt'  => 'Maximisez vos chances d\'obtenir des cartes rares grâce à nos conseils. Comprendre les taux de drop et les multi-rares pour mieux appréhender chaque ouverture.',
                            'date'     => '20 mai 2026',
                            'author'   => 'Équipe PokeHub',
                            'icon'     => 'M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.4-20.8-29.6-20.8H272.5L349.4 44.6z',
                            'iconVb'   => '448',
                        ],
                        [
                            'image'    => asset('images/rayquaza.jpg'),
                            'tag'      => 'Collection',
                            'tagColor' => 'var(--green)',
                            'title'    => 'Les 10 cartes les plus recherchées en 2026',
                            'excerpt'  => 'De Dracaufeu à Rayquaza, découvrez le classement des cartes les plus convoitées par les collectionneurs cette année et leur estimation de valeur.',
                            'date'     => '15 mai 2026',
                            'author'   => 'Équipe PokeHub',
                            'icon'     => 'M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.2 329l104.2-103.1c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L388.2 150.3 316.9 18z',
                            'iconVb'   => '576',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($articles as $i => $article)
                        <article class="news-card group rounded-2xl overflow-hidden"
                                 style="background: var(--bg-card); border: 1px solid var(--border); transition: border-color 0.3s, transform 0.3s;"
                                 onmouseover="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.transform='translateY(-4px)'"
                                 onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'">

                            {{-- Image --}}
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $article['image'] }}" alt=""
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     style="filter: brightness(0.7) saturate(1.1);" />
                                <div class="absolute inset-0" style="background: linear-gradient(180deg, transparent 40%, var(--bg-card) 100%);"></div>
                                <span class="absolute top-3 left-3 text-[11px] font-bold px-2.5 py-1 rounded-full"
                                      style="background: rgba(0,0,0,0.6); color: {{ $article['tagColor'] }}; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.08);">
                                    {{ $article['tag'] }}
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="p-5 space-y-3">
                                <h3 class="text-[15px] font-bold leading-snug line-clamp-2" style="color: var(--text-primary);">
                                    {{ $article['title'] }}
                                </h3>
                                <p class="text-[13px] leading-relaxed line-clamp-3" style="color: var(--text-secondary);">
                                    {{ $article['excerpt'] }}
                                </p>
                                <div class="flex items-center justify-between pt-2" style="border-top: 1px solid var(--border);">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: var(--accent-bg);">
                                            <svg class="w-3 h-3" style="color: var(--text-muted);" fill="currentColor" viewBox="0 0 {{ $article['iconVb'] }} 512"><path d="{{ $article['icon'] }}"/></svg>
                                        </div>
                                        <span class="text-[11px] font-medium" style="color: var(--text-muted);">{{ $article['author'] }}</span>
                                    </div>
                                    <span class="text-[11px]" style="color: var(--text-muted);">{{ $article['date'] }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ CTA ══════════════════════════════════════════════════ --}}
        <section class="py-14 px-8">
            <div class="max-w-3xl mx-auto text-center rounded-2xl overflow-hidden relative py-14 px-8"
                 style="background: linear-gradient(145deg, var(--bg-card) 0%, var(--bg-elevated) 100%); border: 1px solid var(--border);">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-px"
                     style="background: linear-gradient(90deg, transparent, var(--gold), transparent);"></div>
                <h2 class="text-2xl font-extrabold text-white mb-3">Prêt à constituer ta collection ?</h2>
                <p class="text-sm max-w-md mx-auto mb-8" style="color: var(--text-muted);">
                    Crée ton compte gratuitement et commence à ouvrir des boosters pour découvrir des cartes rares.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('register') }}" class="btn btn-ghost">Créer un compte</a>
                    <a href="{{ route('open.index') }}" class="btn btn-primary">Ouvrir maintenant</a>
                </div>
            </div>
        </section>

        <div class="h-8"></div>
    </div>
</x-app-layout>
