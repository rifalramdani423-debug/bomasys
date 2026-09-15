<x-boma-layout>
    <div class="flex flex-col space-y-6 pb-10">
        
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div>
                <h1 class="text-2xl font-black text-white mb-1">Pusat Sampah Akun 🗑️</h1>
                <p class="text-sm text-zinc-400">Daftar seluruh akun sistem (Karyawan & Klien) yang dinonaktifkan.</p>
            </div>
            <a href="{{ route('superadmin.karyawan') }}" class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center border border-zinc-700">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Manajemen Karyawan
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl flex items-center text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Tampilan Kartu (Mobile & Tablet Kecil) -->
            <div class="md:hidden divide-y divide-zinc-800">
                @forelse ($sampah_list as $item)
                    <div class="p-4 space-y-3">
                        <div>
                            <div class="font-bold text-white text-base mb-0.5">{{ $item->name }}</div>
                            <div class="text-[10px] text-zinc-500 font-mono break-all">Email: {{ $item->email }}</div>
                        </div>
                        <div>
                            <div class="text-zinc-300 font-bold text-sm mb-1">{{ $item->nama_perusahaan ?? $item->divisi ?? '-' }}</div>
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->role === 'klien' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                {{ $item->role }}
                            </span>
                        </div>
                        <div class="text-xs font-mono text-red-400">
                            Dihapus: {{ $item->deleted_at->format('d M Y, H:i') }}
                        </div>
                        <div class="flex gap-2 pt-1">
                            <form action="{{ route('superadmin.sampah.restore', $item->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 px-3 py-2 rounded text-xs font-bold transition border border-emerald-500/20">
                                    Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('superadmin.sampah.force-delete', $item->id) }}" method="POST" class="form-hapus flex-1" data-pesan="PERINGATAN: Akun {{ $item->name }} akan dihancurkan secara permanen dari database. Lanjutkan?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-2 rounded text-xs font-bold transition border border-red-500/20">
                                    Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-zinc-500 bg-zinc-900/50">
                        Pusat sampah kosong. Belum ada akun yang dinonaktifkan.
                    </div>
                @endforelse
            </div>

            <!-- Tampilan Tabel (Tablet Besar & Desktop) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="text-[10px] text-zinc-500 uppercase bg-zinc-950 tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Profil & Kategori</th>
                            <th class="px-6 py-4 font-semibold">Divisi / Perusahaan</th>
                            <th class="px-6 py-4 font-semibold">Waktu Dihapus</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($sampah_list as $item)
                            <tr class="hover:bg-zinc-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white mb-0.5 text-base">{{ $item->name }}</div>
                                    <div class="text-[10px] text-zinc-500 font-mono">Email: {{ $item->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-zinc-300 font-bold mb-1">{{ $item->nama_perusahaan ?? $item->divisi ?? '-' }}</div>
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->role === 'klien' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                        {{ $item->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-red-400">
                                    {{ $item->deleted_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <form action="{{ route('superadmin.sampah.restore', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded text-xs font-bold transition border border-emerald-500/20">
                                            Pulihkan
                                        </button>
                                    </form>

                                    <form action="{{ route('superadmin.sampah.force-delete', $item->id) }}" method="POST" class="form-hapus inline-block" data-pesan="PERINGATAN: Akun {{ $item->name }} akan dihancurkan secara permanen dari database. Lanjutkan?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-1.5 rounded text-xs font-bold transition border border-red-500/20">
                                            Hapus Permanen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-zinc-500 bg-zinc-900/50">
                                    Pusat sampah kosong. Belum ada akun yang dinonaktifkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-boma-layout>