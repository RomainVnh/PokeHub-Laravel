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

        <div class="px-10 py-10">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                @foreach($sets as $set)
                    <a href="{{ route('open.booster', $set['id']) }}"
                       x-show="!search || '{{ strtolower(addslashes($set['name'])) }}'.includes(search.toLowerCase())"
                       x-cloak
                       class="edition-card group">
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
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
