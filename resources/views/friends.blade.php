<x-app-layout>
    <x-slot:title>Amis — PokeHub</x-slot:title>

    <div class="min-h-full flex flex-col items-center justify-center px-8 py-16">
        <div class="text-center max-w-md">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                <svg class="w-10 h-10" fill="currentColor" style="color: var(--text-muted);" viewBox="0 0 640 512">
                    <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96H21.3C9.6 320 0 310.4 0 298.7zM405.3 320h-.7c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM416 224a112 112 0 1 0 -224 0 112 112 0 1 0 224 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-white mb-3">Amis</h1>
            <p class="text-sm mb-8" style="color: var(--text-secondary);">
                Bientôt disponible ! Tu pourras ajouter des amis dresseurs et voir leurs collections.
            </p>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold" style="background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-muted);">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
                Fonctionnalité en développement
            </div>
        </div>
    </div>
</x-app-layout>
