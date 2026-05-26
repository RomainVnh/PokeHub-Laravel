<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-2xl font-black text-white mb-1">Connexion</h2>
    <p class="text-sm mb-8" style="color: var(--text-muted);">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-semibold text-white underline underline-offset-2 hover:no-underline">Cr&eacute;er un compte</a>
    </p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-semibold text-white mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="input-dark" placeholder="ton@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-semibold text-white mb-2">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="input-dark" placeholder="Ton mot de passe" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <!-- Remember -->
        <div class="flex items-center mb-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-[rgba(255,255,255,0.15)] bg-[#1E2128] text-white focus:ring-white focus:ring-offset-0" />
                <span class="ms-2 text-sm" style="color: var(--text-secondary);">Se souvenir de moi</span>
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary w-full justify-center text-sm py-3">
            Se connecter
        </button>
    </form>

    @if (Route::has('password.request'))
        <div class="mt-5 text-center">
            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-white underline underline-offset-2 hover:no-underline">
                Mot de passe oubli&eacute; ?
            </a>
        </div>
    @endif
</x-guest-layout>
