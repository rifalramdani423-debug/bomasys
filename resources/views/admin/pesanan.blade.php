<x-boma-layout>
    <div class="flex flex-col space-y-6 pb-10 relative z-0">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white mb-1.5 tracking-tight">Pesanan Masuk 📥</h1>
                <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed max-w-2xl">Tinjau pengajuan sewa baru dari klien dan verifikasi kelengkapan dokumen administratif maupun visual mereka secara teliti.</p>
            </div>
            <div class="bg-red-500/10 border border-red-500/20 px-4 py-2.5 rounded-xl flex items-center shrink-0 w-fit">
                <span class="flex h-2.5 w-2.5 relative mr-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
                <span id="badge-menunggu" class="text-xs sm:text-sm font-bold text-red-500 tracking-wide">{{ $jumlah_menunggu ?? 0 }} Menunggu Review</span>
            </div>
        </div>

        <!-- TABEL PESANAN MASUK -->
        <div class="bg-zinc-900/40 border border-zinc-800 rounded-2xl shadow-2xl overflow-hidden mt-4 backdrop-blur-sm">
            
            <!-- FILTER -->
            <div class="p-5 border-b border-zinc-800 bg-zinc-900/80 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <h3 class="text-sm font-bold text-white tracking-wide">Daftar Antrean Pengajuan</h3>
                
                <form method="GET" action="{{ url('/admin/pesanan') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perusahaan / kode BM..." class="w-full bg-zinc-950 border border-zinc-800 text-zinc-300 text-xs rounded-xl pl-9 pr-3 py-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 placeholder-zinc-600 transition shadow-inner">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <div class="relative w-full sm:w-48">
                        <select name="filter" onchange="this.form.submit()" class="w-full appearance-none bg-zinc-950 border border-zinc-800 text-zinc-300 text-xs rounded-xl pl-4 pr-8 py-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition cursor-pointer shadow-inner font-medium">
                            <option value="menunggu" {{ (request('filter') == 'menunggu' || !request()->has('filter')) ? 'selected' : '' }}>Menunggu ACC</option>
                            <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua Pesanan</option>
                            <option value="aktif" {{ request('filter') == 'aktif' ? 'selected' : '' }}>Klien Masa Sewa</option>
                            <option value="riwayat" {{ request('filter') == 'riwayat' ? 'selected' : '' }}>Riwayat Klien Lama</option>
                        </select>
                        <svg class="w-4 h-4 text-zinc-500 absolute right-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </form>
            </div>
            
            <!-- TAMPILAN KARTU (MOBILE) -->
            <div id="kartu-pesanan-body" class="lg:hidden divide-y divide-zinc-800/50">
                @forelse ($pesanan_masuk as $pesanan)
                    <div class="p-5 space-y-4 hover:bg-zinc-800/20 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-zinc-800 border border-zinc-700 text-white font-black text-sm">
                                    {{ strtoupper(substr($pesanan->user?->nama_perusahaan ?: ($pesanan->user?->name ?? 'K'), 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-white text-sm sm:text-base truncate">{{ $pesanan->user?->nama_perusahaan ?: ($pesanan->user?->name ?? 'Klien Dihapus') }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] sm:text-xs text-zinc-400 font-medium">PIC: {{ $pesanan->user->name ?? '-' }}</span>
                                        <span class="text-[10px] text-zinc-600">&bull;</span>
                                        <span class="text-[10px] sm:text-xs text-zinc-500 font-mono">{{ $pesanan->user?->no_wa ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                                <div class="flex flex-col items-end bg-zinc-900 border border-zinc-800 px-2 py-1 rounded-md shrink-0 shadow-sm">
                                    <span class="text-[10px] font-bold text-zinc-300">{{ $pesanan->created_at->format('d M Y') }}</span>
                                    <span class="text-[9px] text-zinc-500 font-mono mt-0.5">{{ $pesanan->created_at->format('H:i:s') }} WIB</span>
                                </div>
                        </div>

                        <div class="bg-zinc-950/50 rounded-xl p-4 border border-zinc-800/50">
                            <p class="text-[9px] text-zinc-500 uppercase font-black tracking-wider mb-2.5">Titik Diminta & Lokasi</p>
                            <div class="flex flex-col gap-2">
                                @foreach ($pesanan->details as $detail)
                                    <div class="flex items-start gap-2 bg-zinc-900/50 p-2 rounded-lg border border-zinc-800/80">
                                        <span class="bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-2 py-0.5 rounded text-[10px] font-bold font-mono shrink-0">
                                            {{ $detail->kode_titik }}
                                        </span>
                                        <span class="text-[11px] text-zinc-300 leading-snug break-words">
                                            {{ $detail->billboard?->lokasi ?? 'Lokasi tidak ditemukan' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3 flex gap-2">
                                @if(isset($pesanan->butuh_jasa_desain) && $pesanan->butuh_jasa_desain)
                                    <div class="text-[9px] text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20 inline-block px-2 py-1 rounded-md">+ Jasa Desain BOMA</div>
                                @elseif(isset($pesanan->file_preview) && $pesanan->file_preview)
                                    <div class="text-[9px] text-emerald-400 font-bold bg-emerald-500/10 border border-emerald-500/20 inline-block px-2 py-1 rounded-md">Materi Sendiri</div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="text-[9px] text-zinc-500 uppercase font-black tracking-wider mb-1">Jadwal Sewa</p>
                                <div class="text-xs text-white font-medium">{{ \Carbon\Carbon::parse($pesanan->mulai_sewa)->format('d M Y') }} <span class="text-zinc-600 mx-1">&rarr;</span> <span class="text-zinc-400">{{ \Carbon\Carbon::parse($pesanan->selesai_sewa)->format('d M Y') }}</span></div>
                            </div>
                            
                            @if($pesanan->user?->npwp && $pesanan->user?->npwp !== 'Telah Diunggah (File)')
                                <div class="inline-flex items-center space-x-1.5 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-lg text-emerald-500 text-[10px] font-bold shrink-0 w-fit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Tervaluasi OCR</span>
                                </div>
                            @else
                                <div class="inline-flex items-center space-x-1.5 bg-orange-500/10 border border-orange-500/20 px-3 py-1.5 rounded-lg text-orange-400 text-[10px] font-bold shrink-0 w-fit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Validasi Manual</span>
                                </div>
                            @endif
                        </div>

                        <button type="button"
                            onclick="bukaModalTinjau(this)"
                            data-id="{{ $pesanan->id }}"
                            data-klien="{{ $pesanan->user?->name ?? 'Klien Dihapus' }}"
                            data-perusahaan="{{ $pesanan->user?->nama_perusahaan ?? '-' }}"
                            data-wa="{{ $pesanan->user?->no_wa ?? 'Tidak ada' }}"
                            data-nik="{{ $pesanan->user?->nik ?? 'Tidak Terdeteksi' }}"
                            data-npwp="{{ $pesanan->user?->npwp ?? 'Tidak Terdeteksi' }}"
                            data-nomor="{{ $pesanan->nomor_pengajuan }}"
                            data-titik-json="{{ $pesanan->details->map(fn($d) => [
                                'kode' => $d->kode_titik,
                                'lokasi' => $d->billboard?->lokasi ?? 'Data titik tidak ditemukan',
                                'ukuran' => $d->billboard?->ukuran ?? '-',
                                'jenis_ooh' => $d->billboard?->jenis_ooh ?? '-',
                                'kab_kota' => $d->billboard?->kab_kota ?? '-',
                                'harga_per_bulan' => $d->billboard?->harga_per_bulan,
                            ])->values()->toJson() }}"
                            data-mulai="{{ \Carbon\Carbon::parse($pesanan->mulai_sewa)->format('d M Y') }}"
                            data-selesai="{{ \Carbon\Carbon::parse($pesanan->selesai_sewa)->format('d M Y') }}"
                            data-berkas="{{ ($dokumenByUser[$pesanan->user_id] ?? collect())->values()->toJson() }}"
                            data-jasa-desain="{{ $pesanan->jasa_desain ? '1' : '0' }}"
                            data-link-desain="{{ $pesanan->link_desain ?? '' }}"
                            data-file-preview="{{ $pesanan->file_preview ?? '' }}"
                            class="mt-2 w-full flex items-center justify-center bg-red-600 hover:bg-red-500 text-white px-4 py-3 rounded-xl text-xs font-bold transition shadow-lg shadow-red-500/20">
                            Tinjau Pengajuan &rarr;
                        </button>
                    </div>
                @empty
                    <div class="px-6 py-12 flex flex-col items-center justify-center text-zinc-500 bg-zinc-900/30">
                        <svg class="w-12 h-12 mb-3 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-sm">Belum ada pesanan masuk saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- TAMPILAN TABEL (DESKTOP) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="text-[10px] text-zinc-500 uppercase font-black bg-zinc-900/80 tracking-widest border-b border-zinc-800">
                        <tr>
                            <th class="px-6 py-4">Informasi Klien</th>
                            <th class="px-6 py-4 min-w-[250px]">Titik Diminta & Lokasi</th>
                            <th class="px-6 py-4">Jadwal Pengajuan</th>
                            <th class="px-6 py-4 text-center">Verifikasi KTP/NPWP</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-pesanan-body" class="divide-y divide-zinc-800/60">
                        @forelse ($pesanan_masuk as $pesanan)
                            <tr class="hover:bg-zinc-800/30 transition duration-200">
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-4">
                                        <div class="h-10 w-10 shrink-0 items-center justify-center rounded-full bg-zinc-800 border border-zinc-700 text-white font-black text-sm hidden xl:flex mt-1">
                                            {{ strtoupper(substr($pesanan->user?->nama_perusahaan ?: ($pesanan->user?->name ?? 'K'), 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white mb-0.5 text-sm">{{ $pesanan->user?->nama_perusahaan ?: ($pesanan->user?->name ?? 'Klien Dihapus') }}</div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] text-zinc-400">PIC: {{ $pesanan->user->name ?? '-' }}</span>
                                                <span class="text-[10px] text-zinc-600">&bull;</span>
                                                <span class="text-[10px] text-zinc-500 font-mono">{{ $pesanan->user?->no_wa ?? '-' }}</span>
                                            </div>
                                            <div class="text-[10px] text-zinc-600 font-medium mt-1.5">{{ $pesanan->created_at->diffForHumans() }}</div><div class="text-[10px] text-zinc-400 font-mono mt-1.5 bg-zinc-950 inline-block px-2 py-1 rounded border border-zinc-800 shadow-inner">
                                                <span class="text-white font-bold">{{ $pesanan->created_at->format('d M Y') }}</span> &bull; {{ $pesanan->created_at->format('H:i:s') }} WIB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-5 align-top">
                                    <div class="flex flex-col gap-2 mb-2">
                                        @foreach ($pesanan->details as $detail)
                                            <div class="flex items-start gap-2">
                                                <span class="bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-2 py-0.5 rounded text-[10px] font-bold font-mono shrink-0">
                                                    {{ $detail->kode_titik }}
                                                </span>
                                                <span class="text-[11px] text-zinc-300 leading-snug line-clamp-2" title="{{ $detail->billboard?->lokasi }}">
                                                    {{ $detail->billboard?->lokasi ?? 'Lokasi tidak ditemukan' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-2 flex gap-2">
                                        @if(isset($pesanan->butuh_jasa_desain) && $pesanan->butuh_jasa_desain)
                                            <div class="text-[9px] text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20 inline-block px-2 py-0.5 rounded">+ Jasa Desain BOMA</div>
                                        @elseif(isset($pesanan->file_preview) && $pesanan->file_preview)
                                            <div class="text-[9px] text-emerald-400 font-bold bg-emerald-500/10 border border-emerald-500/20 inline-block px-2 py-0.5 rounded">Materi Sendiri</div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-6 py-5 align-top">
                                    <div class="flex flex-col gap-1.5 mt-1">
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="text-[10px] text-zinc-500 font-bold uppercase w-10">Mulai</span>
                                            <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($pesanan->mulai_sewa)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="text-[10px] text-zinc-500 font-bold uppercase w-10">Akhir</span>
                                            <span class="text-zinc-400">{{ \Carbon\Carbon::parse($pesanan->selesai_sewa)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-5 text-center align-top">
                                    <div class="mt-1">
                                        @if($pesanan->user?->npwp && $pesanan->user?->npwp !== 'Telah Diunggah (File)') 
                                            <div class="inline-flex items-center space-x-1.5 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-xl text-emerald-500 text-[10px] font-bold cursor-help" title="KTP & NPWP Lengkap & Terbaca">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>Valid OCR</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center space-x-1.5 bg-orange-500/10 border border-orange-500/20 px-3 py-1.5 rounded-xl text-orange-400 text-[10px] font-bold cursor-help" title="Menunggu atau Perlu Validasi Manual">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>Manual Cek</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-6 py-5 text-right align-top">
                                    <button type="button" 
                                        onclick="bukaModalTinjau(this)" 
                                        data-id="{{ $pesanan->id }}"
                                        data-klien="{{ $pesanan->user?->name ?? 'Klien Dihapus' }}"
                                        data-perusahaan="{{ $pesanan->user?->nama_perusahaan ?? '-' }}"
                                        data-wa="{{ $pesanan->user?->no_wa ?? 'Tidak ada' }}"
                                        data-nik="{{ $pesanan->user?->nik ?? 'Tidak Terdeteksi' }}"
                                        data-npwp="{{ $pesanan->user?->npwp ?? 'Tidak Terdeteksi' }}"
                                        data-nomor="{{ $pesanan->nomor_pengajuan }}"
                                        data-titik-json="{{ $pesanan->details->map(fn($d) => [
                                            'kode' => $d->kode_titik,
                                            'lokasi' => $d->billboard?->lokasi ?? 'Data titik tidak ditemukan',
                                            'ukuran' => $d->billboard?->ukuran ?? '-',
                                            'jenis_ooh' => $d->billboard?->jenis_ooh ?? '-',
                                            'kab_kota' => $d->billboard?->kab_kota ?? '-',
                                            'harga_per_bulan' => $d->billboard?->harga_per_bulan,
                                        ])->values()->toJson() }}"
                                        data-mulai="{{ \Carbon\Carbon::parse($pesanan->mulai_sewa)->format('d M Y') }}"
                                        data-selesai="{{ \Carbon\Carbon::parse($pesanan->selesai_sewa)->format('d M Y') }}"
                                        data-berkas="{{ ($dokumenByUser[$pesanan->user_id] ?? collect())->values()->toJson() }}"
                                        data-jasa-desain="{{ $pesanan->jasa_desain ? '1' : '0' }}"
                                        data-link-desain="{{ $pesanan->link_desain ?? '' }}"
                                        data-file-preview="{{ $pesanan->file_preview ?? '' }}"
                                        class="mt-1 inline-flex items-center bg-red-600 hover:bg-red-500 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-lg shadow-red-500/20 transform hover:-translate-y-0.5">
                                        Tinjau &rarr;
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="baris-kosong">
                                <td colspan="5" class="px-6 py-16 text-center text-zinc-500 bg-zinc-900/30">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    Belum ada pesanan masuk saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TINJAU -->
    <!-- MODAL TINJAU -->
    <div id="modal-tinjau" class="fixed inset-0 flex items-start pt-10 justify-center bg-black/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 p-3 sm:p-6 overflow-y-auto" style="z-index: 999999 !important;">
        
        <div class="bg-zinc-900 border border-zinc-700 rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[85vh]" id="modal-content">
            
            <div class="px-4 sm:px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-950 flex-shrink-0">
                <div class="min-w-0">
                    <h3 class="text-lg font-black text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Verifikasi & Validasi Pesanan
                    </h3>
                    <p class="text-xs text-zinc-500 font-mono mt-0.5">No. Pengajuan: <span id="modal-nomor" class="text-zinc-300 font-bold">-</span></p>
                </div>
                <button type="button" onclick="tutupModalTinjau()" class="text-zinc-500 hover:text-white transition shrink-0 ml-3 bg-zinc-800 p-2 rounded-lg hover:bg-red-500 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="form-keputusan" method="POST" action="" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                <div class="p-4 sm:p-6 overflow-y-auto custom-scrollbar flex-1 space-y-5">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800 text-sm">
                            <div>
                                <p class="text-[10px] text-zinc-500 uppercase font-bold mb-0.5">Perusahaan / Instansi</p>
                                <p class="text-white font-black text-base" id="modal-perusahaan">-</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mt-3 bg-zinc-900/50 p-3 rounded-lg border border-zinc-800/50">
                                <div><p class="text-[10px] text-zinc-500 uppercase font-bold mb-0.5">Nama PIC</p><p class="text-zinc-300 font-medium text-xs" id="modal-klien">-</p></div>
                                <div><p class="text-[10px] text-zinc-500 uppercase font-bold mb-0.5">Kontak (WA)</p><p class="text-zinc-300 font-medium text-xs" id="modal-wa">-</p></div>
                                <div><p class="text-[10px] text-zinc-500 uppercase font-bold mb-0.5">NIK (KTP)</p><p class="text-emerald-400 font-mono font-bold text-xs" id="modal-nik-ocr">-</p></div>
                                <div><p class="text-[10px] text-zinc-500 uppercase font-bold mb-0.5">NPWP</p><p class="text-emerald-400 font-mono font-bold text-xs" id="modal-npwp-ocr">-</p></div>
                            </div>
                            <div class="mt-3 border-t border-zinc-800 pt-3">
                                <p class="text-[10px] text-zinc-500 uppercase font-bold mb-1">Jadwal Sewa</p>
                                <p class="text-white font-bold text-xs bg-zinc-900 inline-block px-2 py-1 rounded" id="modal-jadwal">-</p>
                            </div>
                            <div class="mt-3 pt-3 border-t border-zinc-800">
                                <p class="text-[10px] text-zinc-500 uppercase font-bold mb-2">Berkas Lampiran</p>
                                <div id="modal-berkas-list" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                            </div>
                        </div>

                        <div class="bg-zinc-900 p-4 rounded-xl border border-zinc-800 flex flex-col">
                            <div class="flex justify-between items-center mb-3 border-b border-zinc-800 pb-2">
                                <h3 class="text-xs font-black text-white uppercase tracking-wider">Materi Visual</h3>
                                <span id="badge-status-desain" class="px-2 py-0.5 rounded text-[10px] font-bold">-</span>
                            </div>

                            <div class="flex-1 bg-zinc-950 rounded-xl border border-zinc-800 overflow-hidden flex items-center justify-center mb-3 min-h-[120px]">
                                <div id="modal-jasa-desain" class="text-center p-3 hidden">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-500/50 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    <p class="text-[10px] sm:text-xs text-yellow-500/70 font-medium">Klien meminta Jasa Desain BOMA.<br>Biaya akan disesuaikan.</p>
                                </div>

                                <div id="modal-materi-sendiri" class="w-full h-full hidden relative group">
                                    <span class="absolute top-2 left-2 bg-black/80 text-white text-[9px] font-bold px-2 py-1 rounded z-10">PREVIEW</span>
                                    <a id="modal-link-img" href="#" target="_blank" class="w-full h-full flex items-center justify-center p-2">
                                        <img id="modal-img-preview" src="" class="max-w-full max-h-32 sm:max-h-48 object-contain group-hover:scale-105 transition duration-300">
                                    </a>
                                    <p id="teks-no-image" class="text-xs text-zinc-600 font-bold uppercase tracking-widest text-center mt-12 hidden">TIDAK ADA PREVIEW</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-[10px] text-zinc-500 font-bold mb-1 uppercase tracking-wider">Link File Mentah</p>
                                <div id="div-link-internal" class="hidden">
                                    <p class="text-xs text-zinc-400 italic">Dikerjakan oleh tim internal.</p>
                                </div>
                                <div id="div-link-klien" class="hidden">
                                    <a id="modal-link-materi" href="#" target="_blank" class="text-xs text-blue-500 hover:text-blue-400 font-bold truncate block bg-blue-500/10 p-2 rounded border border-blue-500/20 hover:bg-blue-500/20 transition text-center">
                                        🔗 Buka Link Mentahan Klien
                                    </a>
                                </div>
                                <div id="div-no-link" class="hidden">
                                    <p class="text-xs text-red-400 font-medium">Link tidak disertakan oleh klien.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-zinc-800 pt-4">
                        <h4 class="text-sm font-black text-white mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Detail Titik Reklame & Lokasi
                        </h4>
                        <div id="modal-titik-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        </div>
                    </div>

                    <div class="border-t border-zinc-800 pt-4">
                        <h4 class="text-sm font-black text-white mb-3">Pilih Keputusan Tindak Lanjut</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative flex cursor-pointer rounded-xl border border-zinc-700 bg-zinc-900/50 p-3 sm:p-4 shadow-sm hover:border-emerald-500 focus-within:border-emerald-500 transition-colors">
                                <input type="radio" name="keputusan" value="acc" class="peer sr-only" onchange="toggleCatatan()" checked>
                                <div class="flex items-center w-full">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-zinc-500 bg-zinc-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition">
                                        <svg class="h-3 w-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <span class="block text-sm font-bold text-white peer-checked:text-emerald-400 truncate">Setujui Pesanan</span>
                                        <span class="block text-[10px] text-zinc-500 mt-0.5 truncate">Lanjut ke input Harga Final</span>
                                    </div>
                                </div>
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-emerald-500 pointer-events-none transition" aria-hidden="true"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-zinc-700 bg-zinc-900/50 p-3 sm:p-4 shadow-sm hover:border-amber-500 focus-within:border-amber-500 transition-colors">
                                <input type="radio" name="keputusan" value="revisi" class="peer sr-only" onchange="toggleCatatan()">
                                <div class="flex items-center w-full">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-zinc-500 bg-zinc-800 peer-checked:border-amber-500 peer-checked:bg-amber-500 transition">
                                        <svg class="h-3 w-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <span class="block text-sm font-bold text-white peer-checked:text-amber-400 truncate">Minta Revisi</span>
                                        <span class="block text-[10px] text-zinc-500 mt-0.5 truncate">Titik aman, perbaiki dokumen</span>
                                    </div>
                                </div>
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-amber-500 pointer-events-none transition" aria-hidden="true"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-zinc-700 bg-zinc-900/50 p-3 sm:p-4 shadow-sm hover:border-red-500 focus-within:border-red-500 transition-colors">
                                <input type="radio" name="keputusan" value="tolak" class="peer sr-only" onchange="toggleCatatan()">
                                <div class="flex items-center w-full">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-zinc-500 bg-zinc-800 peer-checked:border-red-500 peer-checked:bg-red-500 transition">
                                        <svg class="h-3 w-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <span class="block text-sm font-bold text-white peer-checked:text-red-500 truncate">Tolak Permanen</span>
                                        <span class="block text-[10px] text-zinc-500 mt-0.5 truncate">Titik akan dilepaskan</span>
                                    </div>
                                </div>
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-red-500 pointer-events-none transition" aria-hidden="true"></div>
                            </label>
                        </div>

                        <div id="area-catatan" class="mt-4 hidden transition-all duration-300">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Tuliskan Catatan Detail / Alasan *</label>
                            <textarea name="catatan_admin" id="input-catatan" rows="2" placeholder="Contoh: KTP kurang jelas, tolong upload ulang..." class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-lg p-3 focus:border-red-500 outline-none transition custom-scrollbar"></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-4 border-t border-zinc-800 bg-zinc-950 flex flex-col sm:flex-row sm:justify-end items-center gap-3 flex-shrink-0">
                    <p class="text-[10px] text-zinc-500 italic mr-auto hidden sm:block">Pastikan memeriksa seluruh data secara teliti.</p>
                    <button type="button" onclick="tutupModalTinjau()" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg transition">Batal</button>
                    <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded-lg transition shadow-[0_0_15px_rgba(220,38,38,0.3)]">
                        Simpan Keputusan &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #18181b; border-radius: 8px;}
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 8px; }
</style>

<script>
    let isModalOpen = false; 

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-tinjau');
        if (modal) {
            document.body.appendChild(modal);
        }
    });

    async function fetchPesananRealTime() {
        if (isModalOpen) return; 
        try {
            let response = await fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            let htmlText = await response.text();
            let parser = new DOMParser();
            let doc = parser.parseFromString(htmlText, 'text/html');
            
            let newTbodyHTML = doc.querySelector('#tabel-pesanan-body').innerHTML;
            let currentTbody = document.querySelector('#tabel-pesanan-body');

            let newKartuHTML = doc.querySelector('#kartu-pesanan-body').innerHTML;
            let currentKartu = document.querySelector('#kartu-pesanan-body');

            let newBadgeHTML = doc.querySelector('#badge-menunggu').innerHTML;
            let currentBadge = document.querySelector('#badge-menunggu');

            if (currentTbody.innerHTML.trim() !== newTbodyHTML.trim()) {
                currentTbody.innerHTML = newTbodyHTML;
                currentKartu.innerHTML = newKartuHTML;

                if (!currentTbody.querySelector('#baris-kosong')) {
                    let barisPertama = currentTbody.querySelector('tr');
                    if (barisPertama) {
                        barisPertama.classList.add('bg-blue-900/40');
                        setTimeout(() => barisPertama.classList.remove('bg-blue-900/40'), 2500);
                    }
                    let kartuPertama = currentKartu.firstElementChild;
                    if (kartuPertama) {
                        kartuPertama.classList.add('bg-blue-900/40');
                        setTimeout(() => kartuPertama.classList.remove('bg-blue-900/40'), 2500);
                    }
                }
            }
            if (currentBadge.innerHTML.trim() !== newBadgeHTML.trim()) {
                currentBadge.innerHTML = newBadgeHTML;
            }
        } catch (error) {
            console.error("Sistem gagal memuat pembaruan otomatis:", error);
        }
    }

    setInterval(fetchPesananRealTime, 5000); 

    function toggleCatatan() {
        let opsi = document.querySelector('input[name="keputusan"]:checked').value;
        let areaCatatan = document.getElementById('area-catatan');
        let inputCatatan = document.getElementById('input-catatan');
        if (opsi === 'revisi' || opsi === 'tolak') {
            areaCatatan.classList.remove('hidden');
            inputCatatan.required = true;
        } else {
            areaCatatan.classList.add('hidden');
            inputCatatan.required = false;
            inputCatatan.value = '';
        }
    }

    function formatNPWP(value) {
        if (!value || value === 'Telah Diunggah (File)' || value === 'Tidak Terdeteksi') return value;
        let numbers = value.replace(/\D/g, ''); 
        if (numbers.length >= 15) { 
            let clean15 = numbers.substring(0, 15);
            return clean15.replace(/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/, '$1.$2.$3.$4-$5.$6');
        }
        return value;
    }

    function bukaModalTinjau(btn) {
        isModalOpen = true; 
        document.body.style.overflow = 'hidden'; 
        
        let id = btn.getAttribute('data-id');
        let nomor = btn.getAttribute('data-nomor');
        let klien = btn.getAttribute('data-klien');
        let perusahaan = btn.getAttribute('data-perusahaan');
        let wa = btn.getAttribute('data-wa');
        let mulai = btn.getAttribute('data-mulai');
        let selesai = btn.getAttribute('data-selesai');
        let nikOcr = btn.getAttribute('data-nik');
        let npwpOcr = btn.getAttribute('data-npwp');
        let berkasJson = btn.getAttribute('data-berkas');
        let berkasList = JSON.parse(berkasJson || '[]');
        let titikJson = btn.getAttribute('data-titik-json');
        let titikList = JSON.parse(titikJson || '[]');
        let jasaDesain = btn.getAttribute('data-jasa-desain') === '1';
        let linkDesain = btn.getAttribute('data-link-desain');
        let filePreview = btn.getAttribute('data-file-preview');

        document.getElementById('modal-nomor').innerText = nomor;
        document.getElementById('modal-perusahaan').innerText = perusahaan !== '-' && perusahaan !== '' ? perusahaan : klien;
        document.getElementById('modal-klien').innerText = klien;
        document.getElementById('modal-wa').innerText = wa;
        document.getElementById('modal-jadwal').innerText = mulai + ' s/d ' + selesai;
        document.getElementById('modal-nik-ocr').innerText = nikOcr;
        document.getElementById('modal-npwp-ocr').innerText = formatNPWP(npwpOcr);

        let containerTitik = document.getElementById('modal-titik-list');
        containerTitik.innerHTML = '';
        if (titikList.length > 0) {
            titikList.forEach(function(t) {
                let hargaText = t.harga_per_bulan ? 'Rp ' + new Intl.NumberFormat('id-ID').format(t.harga_per_bulan) + ' / bulan' : '-';
                let cardHtml = `<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-zinc-950 border border-zinc-800 rounded-xl p-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <span class="bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-2 py-1 rounded text-xs font-bold font-mono shrink-0">${t.kode}</span>
                        <div class="min-w-0">
                            <p class="text-white text-sm font-bold truncate" title="${t.lokasi}">${t.lokasi}</p>
                            <p class="text-[10px] text-zinc-500 mt-0.5">${t.kab_kota} &middot; ${t.jenis_ooh} &middot; Ukuran ${t.ukuran}</p>
                        </div>
                    </div>
                    <span class="text-xs text-emerald-400 font-mono font-bold shrink-0 sm:text-right">${hargaText}</span>
                </div>`;
                containerTitik.innerHTML += cardHtml;
            });
        } else {
            containerTitik.innerHTML = `<p class="text-xs text-zinc-500 italic col-span-2">Tidak ada titik reklame pada pengajuan ini.</p>`;
        }

        document.getElementById('form-keputusan').action = '/admin/pesanan/' + id + '/proses';

        let badgeDesain = document.getElementById('badge-status-desain');
        let divJasa = document.getElementById('modal-jasa-desain');
        let divSendiri = document.getElementById('modal-materi-sendiri');
        let divLinkInternal = document.getElementById('div-link-internal');
        let divLinkKlien = document.getElementById('div-link-klien');
        let divNoLink = document.getElementById('div-no-link');
        let linkHref = document.getElementById('modal-link-materi');

        if (jasaDesain) {
            badgeDesain.innerText = 'MINTA DESAIN BOMA';
            badgeDesain.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-500/20 text-yellow-500 border border-yellow-500/30';
            divJasa.classList.remove('hidden');
            divSendiri.classList.add('hidden');
            divLinkInternal.classList.remove('hidden');
            divLinkKlien.classList.add('hidden');
            divNoLink.classList.add('hidden');
        } else {
            badgeDesain.innerText = 'BAWA MATERI SENDIRI';
            badgeDesain.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-500 border border-emerald-500/30';
            divJasa.classList.add('hidden');
            divSendiri.classList.remove('hidden');
            
            let imgEl = document.getElementById('modal-img-preview');
            let noImgEl = document.getElementById('teks-no-image');
            let linkImg = document.getElementById('modal-link-img');
            
            if (filePreview && filePreview !== '') {
                imgEl.src = '{{ asset("storage") }}/' + filePreview;
                linkImg.href = '{{ asset("storage") }}/' + filePreview;
                imgEl.classList.remove('hidden');
                linkImg.classList.remove('hidden');
                noImgEl.classList.add('hidden');
            } else {
                imgEl.src = '';
                imgEl.classList.add('hidden');
                linkImg.classList.add('hidden');
                noImgEl.classList.remove('hidden');
            }

            divLinkInternal.classList.add('hidden');
            if (linkDesain && linkDesain !== '') {
                divLinkKlien.classList.remove('hidden');
                divNoLink.classList.add('hidden');
                linkHref.href = linkDesain;
            } else {
                divLinkKlien.classList.add('hidden');
                divNoLink.classList.remove('hidden');
            }
        }

        let containerBerkas = document.getElementById('modal-berkas-list');
        containerBerkas.innerHTML = ''; 
        if (berkasList.length > 0) {
            berkasList.forEach(function(doc) {
                let fileUrl = '{{ asset("storage") }}/' + doc.file_path;
                let docHtml = `<a href="${fileUrl}" target="_blank" class="flex items-center justify-between bg-zinc-900 border border-zinc-700 hover:border-blue-500 p-2 rounded-lg transition group"><span class="text-xs font-medium text-zinc-300 group-hover:text-white truncate">${doc.jenis_dokumen}</span><span class="text-[9px] font-bold text-zinc-500 group-hover:text-blue-400 whitespace-nowrap ml-2">Buka &nearr;</span></a>`;
                containerBerkas.innerHTML += docHtml;
            });
        } else {
            containerBerkas.innerHTML = `<p class="text-xs text-zinc-500 italic">Belum ada dokumen administratif.</p>`;
        }

        document.querySelector('input[name="keputusan"][value="acc"]').checked = true;
        toggleCatatan();

        let modal = document.getElementById('modal-tinjau');
        let content = document.getElementById('modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); content.classList.remove('scale-95'); }, 10);
    }

    function tutupModalTinjau() {
        isModalOpen = false; 
        document.body.style.overflow = ''; 
        
        let modal = document.getElementById('modal-tinjau');
        let content = document.getElementById('modal-content');
        modal.classList.add('opacity-0'); content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300); 
    }
</script>
</x-boma-layout>