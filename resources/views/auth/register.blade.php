<x-guest-layout :banner="'banniere4.jpg'">
    <h2 class="text-2xl font-black text-white mb-1">Cr&eacute;er un compte</h2>
    <p class="text-sm mb-8" style="color: var(--text-muted);">
        D&eacute;j&agrave; un compte ?
        <a href="{{ route('login') }}" class="font-semibold text-white underline underline-offset-2 hover:no-underline">Se connecter</a>
    </p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-5">
            <label for="name" class="block text-sm font-semibold text-white mb-2">Nom de dresseur</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="input-dark" placeholder="Sacha" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-semibold text-white mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="input-dark" placeholder="ton@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-semibold text-white mb-2">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="input-dark" placeholder="Ton mot de passe" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-semibold text-white mb-2">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="input-dark" placeholder="Confirme ton mot de passe" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary w-full justify-center text-sm py-3">
            Cr&eacute;er mon compte
        </button>
    </form>
</x-guest-layout>
