<x-boma-layout>
    <div class="max-w-7xl mx-auto pb-10 space-y-6">
        
        <div class="mb-8">
            <h2 class="text-2xl font-black text-white tracking-wide flex items-center">
                <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Pengaturan Profil
            </h2>
            <p class="text-sm text-zinc-400 mt-1">Kelola data diri, informasi perusahaan, dan keamanan akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 w-full h-32 bg-gradient-to-b from-red-600/20 to-transparent pointer-events-none"></div>
                    
                    <div class="relative mt-4 mb-4 group cursor-pointer">
                        <img id="preview_photo" src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=dc2626&color=ffffff&size=150' }}" 
                             alt="Profile Picture" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-zinc-900 shadow-[0_0_15px_rgba(220,38,38,0.3)] transition duration-300">
                    </div>

                    <h3 class="text-lg font-bold text-white uppercase">{{ Auth::user()->name }}</h3>
                    <span class="text-xs font-semibold text-red-500 bg-red-500/10 px-3 py-1 rounded-full border border-red-500/20 mt-2 uppercase tracking-widest">{{ Auth::user()->role ?? 'Klien' }}</span>
                    
                    <p class="text-xs text-zinc-500 mt-4 pt-4 border-t border-zinc-800 w-full">
                        Bergabung sejak: <br>
                        <span class="text-zinc-300 font-medium">{{ Auth::user()->created_at->format('d F Y') }}</span>
                    </p>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="w-full mt-4 space-y-3">
                        @csrf
                        @method('patch')
                        
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <input type="file" name="profile_photo" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)" class="block w-full text-xs text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-white hover:file:bg-zinc-700 transition cursor-pointer">
                        <button type="submit" class="w-full py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded-xl text-xs font-bold transition border border-zinc-700">Perbarui Foto Profil</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2">Informasi Akun & Perusahaan</h3>
                    
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-medium text-zinc-400 mb-1">Nama Lengkap (PIC) *</label>
                                <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-medium text-zinc-400 mb-1">Alamat Email *</label>
                                <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                            </div>

                            <div>
                                <label for="no_wa" class="block text-xs font-medium text-zinc-400 mb-1">Nomor Telepon / WhatsApp</label>
                                <input id="no_wa" name="no_wa" type="text" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                       placeholder="0812xxxxxx" 
                                       value="{{ old('no_wa', Auth::user()->no_wa ?? '') }}" 
                                       class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                            </div>

                            <div>
                                <label for="npwp" class="block text-xs font-medium text-zinc-400 mb-1">Nomor NPWP</label>
                                <input id="npwp" name="npwp" type="text" 
                                       placeholder="XX.XXX.XXX.X-XXX.XXX" 
                                       value="{{ old('npwp', Auth::user()->npwp ?? '') }}" 
                                       maxlength="20"
                                       class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                            </div>

                            <div class="md:col-span-2"> 
                                <label for="nama_perusahaan" class="block text-xs font-medium text-zinc-400 mb-1">Nama Instansi / Perusahaan</label>
                                <input id="nama_perusahaan" name="nama_perusahaan" type="text" 
                                       placeholder="PT / CV / Organisasi" 
                                       value="{{ old('nama_perusahaan', Auth::user()->nama_perusahaan ?? '') }}" 
                                       class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                            </div>

                        </div>

                        <div class="flex items-center justify-end pt-5 border-t border-zinc-800 mt-6 gap-4">
                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-emerald-500 font-medium" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Tersimpan.</p>
                            @endif
                            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm tracking-wide transition shadow-[0_0_15px_rgba(220,38,38,0.3)]">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Perbarui Kata Sandi
                    </h3>
                    
                    <form method="post" action="{{ route('password.update') }}" class="space-y-5 max-w-xl">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-xs font-medium text-zinc-400 mb-1">Kata Sandi Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-medium text-zinc-400 mb-1">Kata Sandi Baru</label>
                            <input id="password" name="password" type="password" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-medium text-zinc-400 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2.5 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600 transition">
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="px-6 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white rounded-xl font-bold text-sm transition border border-zinc-700">
                                Perbarui Sandi
                            </button>
                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-emerald-500 font-medium" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Sandi diperbarui.</p>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview_photo');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const npwpInput = document.getElementById('npwp');
            if (npwpInput) {
                npwpInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    let formatted = '';
                    if (value.length > 0) formatted += value.substring(0, 2);
                    if (value.length > 2) formatted += '.' + value.substring(2, 5);
                    if (value.length > 5) formatted += '.' + value.substring(5, 8);
                    if (value.length > 8) formatted += '.' + value.substring(8, 9);
                    if (value.length > 9) formatted += '-' + value.substring(9, 12);
                    if (value.length > 12) formatted += '.' + value.substring(12, 15);
                    e.target.value = formatted;
                });
            }
        });
    </script>
</x-boma-layout>