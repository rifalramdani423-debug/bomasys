<x-boma-layout>
    <div class="flex flex-col space-y-6 pb-10">
        
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div>
                <h1 class="text-2xl font-black text-white mb-1">Manajemen Akun Internal 🏢</h1>
                <p class="text-sm text-zinc-400">Kelola data karyawan, hak akses (Role), divisi, dan kredensial login sistem.</p>
            </div>
            <button type="button" onclick="bukaModalTambah()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_0_15px_rgba(16,185,129,0.3)] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Tambah Karyawan
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl flex items-center text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl flex items-center text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc pl-5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FILTER & PENCARIAN -->
        <form method="GET" action="{{ route('superadmin.karyawan') }}" class="bg-zinc-900 border border-zinc-800 p-4 rounded-2xl flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-3 w-full">
                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau email..." class="w-full bg-zinc-950 border border-zinc-800 text-white text-xs rounded-xl pl-9 pr-4 py-2.5 focus:border-emerald-500 outline-none transition">
                </div>

                <div class="w-full md:w-56">
                    <select name="divisi" onchange="this.form.submit()" class="w-full bg-zinc-950 border border-zinc-800 text-white text-xs rounded-xl px-4 py-2.5 focus:border-emerald-500 outline-none transition">
                        <option value="">-- Semua Divisi --</option>
                        @foreach($divisi_list as $div)
                            <option value="{{ $div }}" {{ request('divisi') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(request('search') || request('divisi'))
                <a href="{{ route('superadmin.karyawan') }}" class="text-xs text-red-400 hover:text-red-300 font-bold whitespace-nowrap">Reset Filter</a>
            @endif
        </form>

        <!-- TABEL KARYAWAN -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Tampilan Kartu (Mobile & Tablet Kecil) -->
            <div class="md:hidden divide-y divide-zinc-800">
                @forelse ($karyawan_list as $k)
                    <div class="p-4 space-y-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-400 font-bold mr-3 shrink-0">
                                {{ substr($k->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-white text-base truncate">{{ $k->name }}</div>
                                <div class="text-[10px] text-zinc-500 font-mono">NIK: {{ $k->nik ?? 'Belum Diatur' }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="text-zinc-300 font-bold text-sm mb-1">{{ $k->divisi ?? '-' }}</div>
                            <span class="bg-blue-500/10 text-blue-500 border border-blue-500/20 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider">
                                {{ $k->role }}
                            </span>
                        </div>
                        <div class="text-xs space-y-1">
                            <div class="text-zinc-300 break-all">{{ $k->email }}</div>
                            <div class="text-emerald-500 font-mono">{{ $k->no_wa ?? '-' }}</div>
                        </div>
                        <div class="flex gap-2 pt-1">
                            <button type="button"
                                onclick="bukaModalEdit(this)"
                                data-id="{{ $k->id }}"
                                data-nik="{{ $k->nik }}"
                                data-name="{{ $k->name }}"
                                data-email="{{ $k->email }}"
                                data-wa="{{ $k->no_wa }}"
                                data-divisi="{{ $k->divisi }}"
                                data-role="{{ $k->role }}"
                                class="flex-1 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 px-3 py-2 rounded text-xs font-bold transition border border-blue-500/20">
                                Edit
                            </button>
                            @if(auth()->id() != $k->id)
                                <form action="{{ route('superadmin.karyawan.delete', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun karyawan ini?');" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-2 rounded text-xs font-bold transition border border-red-500/20">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-zinc-500 bg-zinc-900/50">
                        Tidak ada data karyawan yang sesuai dengan pencarian.
                    </div>
                @endforelse
            </div>

            <!-- Tampilan Tabel (Tablet Besar & Desktop) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="text-[10px] text-zinc-500 uppercase bg-zinc-950 tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Profil & NIK</th>
                            <th class="px-6 py-4 font-semibold">Divisi & Hak Akses</th>
                            <th class="px-6 py-4 font-semibold">Kontak</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($karyawan_list as $k)
                            <tr class="hover:bg-zinc-800/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-400 font-bold mr-3 shrink-0">
                                            {{ substr($k->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white mb-0.5 text-base">{{ $k->name }}</div>
                                            <div class="text-[10px] text-zinc-500 font-mono flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                                NIK: {{ $k->nik ?? 'Belum Diatur' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-zinc-300 font-bold mb-1">{{ $k->divisi ?? '-' }}</div>
                                    <span class="bg-blue-500/10 text-blue-500 border border-blue-500/20 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider">
                                        {{ $k->role }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-xs text-zinc-300 mb-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $k->email }}
                                    </div>
                                    <div class="text-[11px] text-emerald-500 font-mono flex items-center">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $k->no_wa ?? '-' }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                        <button type="button" 
                                            onclick="bukaModalEdit(this)"
                                            data-id="{{ $k->id }}"
                                            data-nik="{{ $k->nik }}"
                                            data-name="{{ $k->name }}"
                                            data-email="{{ $k->email }}"
                                            data-wa="{{ $k->no_wa }}"
                                            data-divisi="{{ $k->divisi }}"
                                            data-role="{{ $k->role }}"
                                            class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 px-3 py-1.5 rounded text-xs font-bold transition border border-blue-500/20">
                                            Edit
                                        </button>
                                        
                                        @if(auth()->id() != $k->id)
                                            <form action="{{ route('superadmin.karyawan.delete', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun karyawan ini?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-1.5 rounded text-xs font-bold transition border border-red-500/20">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-zinc-500 bg-zinc-900/50">
                                    Tidak ada data karyawan yang sesuai dengan pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL FORM KARYAWAN -->
    <div id="modal-karyawan" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-zinc-900 border border-zinc-700 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col" id="modal-content">
            
            <div class="px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-950/50">
                <h3 class="text-lg font-black text-white" id="modal-title">Tambah Karyawan Baru</h3>
                <button type="button" onclick="tutupModal()" class="text-zinc-500 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="form-karyawan" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    
                    <!-- Info NIK Otomatis -->
                    <div id="info-nik-otomatis" class="bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-400">
                        💡 NIK karyawan akan digenerate secara otomatis oleh sistem dengan format: <code class="text-red-500 font-bold">BOMA-[DIVISI]-{Tahun}-[Urut]</code>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-1.5">Nama Lengkap Karyawan *</label>
                        <input type="text" name="name" id="input-name" required class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-lg px-4 py-2.5 focus:border-emerald-500 outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">Email Aktif (Login) *</label>
                            <input type="email" name="email" id="input-email" required class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-lg px-4 py-2.5 focus:border-emerald-500 outline-none transition">
                        </div>
                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">No. WhatsApp</label>
                            <input type="text" name="no_wa" id="input-wa" class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-lg px-4 py-2.5 focus:border-emerald-500 outline-none transition">
                        </div>
                    </div>

                    <!-- Divisi & Hak Akses Disatukan -->
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-1.5">Divisi & Hak Akses Sistem *</label>
                        <select name="divisi" id="input-divisi" required class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-lg px-4 py-2.5 focus:border-emerald-500 outline-none transition appearance-none">
                            <option value="">-- Pilih Divisi & Hak Akses --</option>
                            <option value="Admin Front Office">Admin (Front Office)</option>
                            <option value="Infrastruktur & Aset">Tim Aset & Infrastruktur</option>
                            <option value="Finance & Accounting">Keuangan / Finance</option>
                            <option value="Operasional & Lapangan">Tim Produksi / Lapangan</option>
                            <option value="Direksi Eksekutif">General Manager / Direktur</option>
                            <option value="Information Technology">Super Admin (Master)</option>
                        </select>
                    </div>

                    <!-- Password dengan Fitur Show/Hide -->
                    <div class="border-t border-zinc-800 pt-4 mt-2">
                        <label class="block text-xs font-bold text-zinc-400 mb-1.5" id="label-password">Password Akun *</label>
                        <div class="relative">
                            <input type="password" name="password" id="input-password" class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-lg pl-4 pr-10 py-2.5 focus:border-emerald-500 outline-none transition">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-white">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-zinc-500 mt-1.5" id="help-password">Buat sandi yang kuat minimal 8 karakter.</p>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/50 flex justify-end space-x-3">
                    <button type="button" onclick="tutupModal()" class="px-4 py-2 text-sm font-bold text-zinc-400 hover:text-white transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-lg transition shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fitur Lihat/Sembunyikan Password
        function togglePassword() {
            let pwdInput = document.getElementById('input-password');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
            } else {
                pwdInput.type = 'password';
            }
        }

        function bukaModalTambah() {
            document.getElementById('modal-title').innerText = 'Tambah Karyawan Baru';
            document.getElementById('form-karyawan').action = '{{ route("superadmin.karyawan.store") }}';
            document.getElementById('form-method').value = 'POST';
            
            document.getElementById('input-name').value = '';
            document.getElementById('input-email').value = '';
            document.getElementById('input-wa').value = '';
            document.getElementById('input-divisi').value = '';
            document.getElementById('input-password').value = '';
            document.getElementById('input-password').required = true;
            
            document.getElementById('label-password').innerText = 'Password Akun *';
            document.getElementById('help-password').innerText = 'Buat sandi yang kuat minimal 8 karakter.';
            document.getElementById('info-nik-otomatis').style.display = 'block';

            tampilkanModal();
        }

        function bukaModalEdit(btn) {
            document.getElementById('modal-title').innerText = 'Edit Data Karyawan';
            
            let id = btn.getAttribute('data-id');
            document.getElementById('form-karyawan').action = '/super-admin/karyawan/update/' + id;
            document.getElementById('form-method').value = 'PUT';

            document.getElementById('input-name').value = btn.getAttribute('data-name');
            document.getElementById('input-email').value = btn.getAttribute('data-email');
            document.getElementById('input-wa').value = btn.getAttribute('data-wa');
            document.getElementById('input-divisi').value = btn.getAttribute('data-divisi');
            
            document.getElementById('input-password').value = '';
            document.getElementById('input-password').required = false;
            document.getElementById('label-password').innerText = 'Reset Password (Opsional)';
            document.getElementById('help-password').innerText = 'Kosongkan jika tidak ingin merubah sandi karyawan ini.';
            document.getElementById('info-nik-otomatis').style.display = 'none'; // Sembunyikan info NIK saat edit

            tampilkanModal();
        }

        function tampilkanModal() {
            let modal = document.getElementById('modal-karyawan');
            let content = document.getElementById('modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function tutupModal() {
            let modal = document.getElementById('modal-karyawan');
            let content = document.getElementById('modal-content');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-boma-layout>