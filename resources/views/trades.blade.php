<x-app-layout>
    <x-slot:title>Échanges — PokeHub</x-slot:title>

    <div class="min-h-full flex flex-col items-center justify-center px-8 py-16">
        <div class="text-center max-w-md">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                <svg class="w-10 h-10" fill="currentColor" style="color: var(--text-muted);" viewBox="0 0 512 512">
                    <path d="M0 96C0 60.7 28.7 32 64 32H196.1c19.1 0 37.4 7.6 50.9 21.1L289.9 96H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM168 288H120c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v40c0 9.5 5.6 18.1 14.2 21.9s18.8 2.3 25.8-4.1l80-72c9.1-8.2 9.8-22.2 1.6-31.3s-22.2-9.8-31.3-1.6L216 327.5V312 288 272 248.5l42.2 38c9.1 8.2 23.2 7.5 31.3-1.6s7.5-23.2-1.6-31.3l-80-72c-7.1-6.4-17.1-7.9-25.8-4.1S168 190.5 168 200v40h0 0v48zm144 0h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H312V200c0-9.5-5.6-18.1-14.2-21.9s-18.8-2.3-25.8 4.1l-80 72c-9.1 8.2-9.8 22.2-1.6 31.3s22.2 9.8 31.3 1.6L264 248.5V264v24 24 15.5l-42.2-38c-9.1-8.2-23.2-7.5-31.3 1.6s-7.5 23.2 1.6 31.3l80 72c7.1 6.4 17.1 7.9 25.8 4.1S312 385.5 312 376V336h0 0V288z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-white mb-3">Échanges</h1>
            <p class="text-sm mb-8" style="color: var(--text-secondary);">
                Bientôt disponible ! Tu pourras échanger tes cartes en double avec d'autres dresseurs.
            </p>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold" style="background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-muted);">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
                Fonctionnalité en développement
            </div>
        </div>
    </div>
</x-app-layout>
