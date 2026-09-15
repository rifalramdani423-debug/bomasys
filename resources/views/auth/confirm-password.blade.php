<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Konfirmasi Password</h2>
        <p class="text-sm text-zinc-400 mt-2">Ini adalah area aman sistem. Mohon konfirmasi password Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Password" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full py-3">
                Konfirmasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
