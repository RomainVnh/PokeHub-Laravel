<x-app-layout>
    <x-slot:title>Mon Profil — PokeHub</x-slot:title>

    <div class="min-h-full">

        {{-- Header --}}
        <div class="px-10 pt-10 pb-8" style="border-bottom: 1px solid var(--border);">
            <span class="label-xs block mb-3" style="color: var(--text-primary);">Compte</span>
            <h1 class="text-3xl font-black mb-2 tracking-tight" style="color: var(--text-primary);">Mon Profil</h1>
            <p class="text-sm" style="color: var(--text-muted);">G&egrave;re tes informations personnelles et tes pr&eacute;f&eacute;rences.</p>
        </div>

        <div class="px-10 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ═══ Avatar selector ══════════════════════════════════ --}}
                <div class="lg:col-span-2 rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);"
                     x-data="{
                        showPicker: false,
                        selectedAvatar: '{{ $user->avatar ?? '' }}',
                        page: 1,
                        perPage: 36,
                        maxPokemon: 251,
                        get totalPages() { return Math.ceil(this.maxPokemon / this.perPage); },
                        get pokemonIds() {
                            const start = (this.page - 1) * this.perPage + 1;
                            const end = Math.min(start + this.perPage - 1, this.maxPokemon);
                            return Array.from({length: end - start + 1}, (_, i) => start + i);
                        },
                        spriteUrl(id) {
                            return 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/' + id + '.png';
                        },
                        select(id) {
                            this.selectedAvatar = this.spriteUrl(id);
                        }
                     }">
                    <div class="flex items-center gap-5 mb-5">
                        {{-- Current avatar --}}
                        <div class="w-20 h-20 rounded-2xl overflow-hidden flex items-center justify-center flex-shrink-0"
                             style="background: var(--bg-card); border: 2px solid var(--border-light);">
                            <template x-if="selectedAvatar">
                                <img :src="selectedAvatar" alt="Avatar" class="w-16 h-16 object-contain" />
                            </template>
                            <template x-if="!selectedAvatar">
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-black" style="background: var(--accent); color: var(--text-inverse);">
                                    {{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}
                                </div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-base font-bold mb-1" style="color: var(--text-primary);">Photo de profil</h2>
                            <p class="text-xs mb-3" style="color: var(--text-muted);">Choisis un Pok&eacute;mon comme avatar.</p>
                            <button type="button" @click="showPicker = !showPicker" class="btn btn-surface btn-sm">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 512 512"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L480 132.3l-97.9-97.9L172.4 241.7zM64 480c-17.7 0-32-14.3-32-32V64c0-17.7 14.3-32 32-32h210.7c17.7 0 32 14.3 32 32v16c0 8.8-7.2 16-16 16H96v320h320V272c0-8.8 7.2-16 16-16h16c8.8 0 16 7.2 16 16v176c0 17.7-14.3 32-32 32H64z"/></svg>
                                Choisir un avatar
                            </button>
                        </div>
                    </div>

                    {{-- Pokemon picker grid --}}
                    <div x-show="showPicker" x-transition class="mt-4">
                        <div class="grid grid-cols-6 sm:grid-cols-9 md:grid-cols-12 gap-2 mb-4 p-4 rounded-xl" style="background: var(--bg-card); border: 1px solid var(--border);">
                            <template x-for="id in pokemonIds" :key="id">
                                <button type="button" @click="select(id)"
                                        class="w-full aspect-square rounded-xl flex items-center justify-center transition-all hover:scale-110 cursor-pointer p-1"
                                        :style="selectedAvatar === spriteUrl(id) ? 'background: var(--accent-bg); border: 2px solid var(--accent); box-shadow: 0 0 12px rgba(255,255,255,0.1);' : 'background: var(--bg-surface); border: 2px solid transparent;'">
                                    <img :src="spriteUrl(id)" :alt="'Pokemon #' + id" class="w-full h-full object-contain" loading="lazy" />
                                </button>
                            </template>
                        </div>

                        {{-- Pagination --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="page = Math.max(1, page - 1)" :disabled="page === 1"
                                        class="btn btn-surface btn-sm" :style="page === 1 ? 'opacity: 0.3; cursor: not-allowed;' : ''">
                                    &larr; Pr&eacute;c&eacute;dent
                                </button>
                                <span class="text-xs" style="color: var(--text-muted);" x-text="'Page ' + page + ' / ' + totalPages"></span>
                                <button type="button" @click="page = Math.min(totalPages, page + 1)" :disabled="page === totalPages"
                                        class="btn btn-surface btn-sm" :style="page === totalPages ? 'opacity: 0.3; cursor: not-allowed;' : ''">
                                    Suivant &rarr;
                                </button>
                            </div>

                            {{-- Save button --}}
                            <form method="POST" action="{{ route('profile.avatar') }}" class="inline">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="avatar" :value="selectedAvatar" />
                                <button type="submit" class="btn btn-primary btn-sm" :disabled="!selectedAvatar">
                                    Sauvegarder l'avatar
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (session('status') === 'avatar-updated')
                        <p class="text-xs text-green-400 font-medium mt-3">Avatar mis &agrave; jour !</p>
                    @endif
                </div>

                {{-- ═══ Update profile ═══════════════════════════════════ --}}
                <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                    <h2 class="text-base font-bold mb-1" style="color: var(--text-primary);">Informations du profil</h2>
                    <p class="text-xs mb-5" style="color: var(--text-muted);">Modifie ton nom de dresseur et ton adresse email.</p>

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
                                <p class="text-xs text-green-400 font-medium">Sauvegard&eacute;.</p>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- ═══ Update password ══════════════════════════════════ --}}
                <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                    <h2 class="text-base font-bold mb-1" style="color: var(--text-primary);">Mot de passe</h2>
                    <p class="text-xs mb-5" style="color: var(--text-muted);">Utilise un mot de passe long et unique pour prot&eacute;ger ton compte.</p>

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

                {{-- ═══ Delete account ═══════════════════════════════════ --}}
                <div class="rounded-2xl p-6" style="background: var(--bg-elevated); border: 1px solid rgba(239,68,68,0.15);">
                    <h2 class="text-base font-bold text-red-400 mb-1">Zone dangereuse</h2>
                    <p class="text-xs mb-5" style="color: var(--text-muted);">La suppression du compte est irr&eacute;versible. Toutes tes donn&eacute;es seront perdues.</p>

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
</x-app-layout>
