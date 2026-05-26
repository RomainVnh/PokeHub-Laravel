<x-guest-layout>
    <h2 class="text-xl font-bold text-white mb-1">Mot de passe oublié</h2>
    <p class="text-sm text-[#6B7280] mb-6">Indique ton email pour recevoir un lien de réinitialisation.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-xs font-semibold text-[#9CA3AF] mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input-dark" placeholder="ton@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
        </div>

        <button type="submit" class="btn btn-yellow w-full justify-center text-sm">
            Envoyer le lien
        </button>
    </form>

    <div class="mt-5 text-center">
        <a href="{{ route('login') }}" class="text-xs font-semibold text-[#D4A843] hover:underline">&larr; Retour à la connexion</a>
    </div>
</x-guest-layout>
