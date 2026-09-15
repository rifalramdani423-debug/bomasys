<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Buat Akun Baru</h2>
        <p class="text-sm text-zinc-400 mt-2">Daftar untuk mulai menyewa billboard</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="block font-medium text-sm text-zinc-300">Nama Penanggung Jawab (PIC)</label>
            <input id="name" class="block mt-1 w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:border-red-600 focus:ring focus:ring-red-600/20 px-4 py-2.5 transition" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block font-medium text-sm text-zinc-300">Email Utama</label>
            <input id="email" class="block mt-1 w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:border-red-600 focus:ring focus:ring-red-600/20 px-4 py-2.5 transition" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <label for="nama_perusahaan" class="block font-medium text-sm text-zinc-300">Nama Perusahaan / Instansi</label>
            <input id="nama_perusahaan" class="block mt-1 w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:border-red-600 focus:ring focus:ring-red-600/20 px-4 py-2.5 transition" type="text" name="nama_perusahaan" :value="old('nama_perusahaan')" required autocomplete="organization" />
            <x-input-error :messages="$errors->get('nama_perusahaan')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="no_wa" class="block font-medium text-sm text-zinc-300">Nomor WhatsApp PIC</label>
            <input id="no_wa" class="block mt-1 w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:border-red-600 focus:ring focus:ring-red-600/20 px-4 py-2.5 transition" type="number" name="no_wa" :value="old('no_wa')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('no_wa')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div x-data="{ show: false }">
                <label for="password" class="block font-medium text-sm text-zinc-300">Password</label>
                <div class="relative mt-1">
                    <input id="password" :type="show ? 'text' : 'password'" class="block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:border-red-600 focus:ring focus:ring-red-600/20 px-4 py-2.5 pr-10 transition" name="password" required autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-500 hover:text-red-500 transition">
                        <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block font-medium text-sm text-zinc-300">Ulangi Password</label>
                <div class="relative mt-1">
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" class="block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:border-red-600 focus:ring focus:ring-red-600/20 px-4 py-2.5 pr-10 transition" name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-500 hover:text-red-500 transition">
                        <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-3 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition duration-200 shadow-lg shadow-red-600/25">
                Daftar Sekarang
            </button>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-zinc-400">Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-red-500 font-semibold hover:text-red-400 transition">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>