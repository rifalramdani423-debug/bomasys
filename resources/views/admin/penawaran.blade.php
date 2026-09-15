<x-boma-layout>
    <div class="flex flex-col space-y-4 md:space-y-6 pb-10 relative z-0">
        
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-2 md:gap-4">
            <div>
                <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-white mb-1.5 tracking-tight">Keputusan Final Harga 🤝</h1>
                <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed max-w-2xl">Input harga kesepakatan akhir beserta rincian transparansi biayanya, lalu delegasikan pengaturan termin pembayaran ke Klien.</p>
            </div>
        </div>

        <div class="bg-zinc-900/40 border border-zinc-800 rounded-xl md:rounded-2xl shadow-2xl overflow-hidden mt-4 backdrop-blur-sm">
            
            <div class="lg:hidden divide-y divide-zinc-800/50">
                @forelse ($penawaran_list as $p)
                    @php
                        $rincianData = json_decode(json_encode($p->rincian_lengkap), true);
                        $titikList = $rincianData['titik'] ?? [];
                    @endphp
                    <div class="p-4 sm:p-5 space-y-4 hover:bg-zinc-800/20 transition">
                        <div class="flex items-start justify-between gap-3 border-b border-zinc-800 pb-3 sm:pb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-zinc-800 border border-zinc-700 text-white font-black text-sm">
                                    {{ strtoupper(substr($p->user?->nama_perusahaan ?: ($p->user?->name ?? 'K'), 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[9px] sm:text-[10px] text-zinc-500 uppercase tracking-wider mb-0.5 font-bold">Perusahaan / Instansi</div>
                                    <div class="font-bold text-white text-sm sm:text-base truncate">{{ $p->user?->nama_perusahaan ?: ($p->user?->name ?? 'Klien Dihapus') }}</div>
                                </div>
                            </div>
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 sm:px-2.5 py-1 rounded-md text-[9px] font-bold shrink-0 whitespace-nowrap shadow-sm">
                                {{ strtoupper($p->status_pengajuan) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="min-w-0">
                                <p class="text-[9px] text-zinc-500 uppercase font-black tracking-wider mb-1">Nama PIC</p>
                                <p class="text-xs text-white font-medium truncate">{{ $p->user->name ?? '-' }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] text-zinc-500 uppercase font-black tracking-wider mb-1">WhatsApp</p>
                                <div class="text-[11px] sm:text-xs text-emerald-500 font-mono font-bold flex items-center truncate">
                                    <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="truncate">{{ $p->user?->no_wa ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-zinc-950/50 rounded-xl p-3 sm:p-4 border border-zinc-800/50 space-y-3">
                            <p class="text-[10px] text-zinc-500 uppercase font-black tracking-wider border-b border-zinc-800 pb-2">Detail Titik Diminta</p>
                            <div class="space-y-3">
                                @foreach ($titikList as $t)
                                    <div class="bg-zinc-900 p-3 rounded-lg border border-zinc-800">
                                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                            <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded text-[10px] font-bold font-mono">{{ $t['kode'] ?? '-' }}</span>
                                            <span class="text-[10px] bg-zinc-800 text-zinc-300 px-2 py-0.5 rounded font-medium">{{ $t['ukuran'] ?? '-' }}</span>
                                        </div>
                                        <p class="text-[11px] sm:text-xs text-zinc-300 font-medium leading-relaxed mb-1">{{ $t['lokasi'] ?? 'Data lokasi tidak ditemukan' }}</p>
                                        <p class="text-[9px] text-zinc-500 uppercase tracking-wider">{{ $t['kab_kota'] ?? '-' }} &middot; {{ $t['jenis_ooh'] ?? '-' }}</p>
                                    </div>
                                @endforeach
                            </div>
                            @if($p->butuh_jasa_desain)
                                <div class="mt-2 inline-block bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-1 rounded-md text-[9px] font-bold">
                                    + JASA DESAIN BOMA
                                </div>
                            @endif
                        </div>

                        <div class="bg-zinc-950/50 rounded-xl p-3 sm:p-4 border border-zinc-800/50">
                             <p class="text-[10px] text-zinc-500 uppercase font-black tracking-wider border-b border-zinc-800 pb-2 mb-3">Jadwal Pengajuan</p>
                             <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                 <div>
                                     <span class="block text-[9px] sm:text-[10px] text-zinc-500 font-bold uppercase mb-0.5">Mulai:</span>
                                     <span class="text-[11px] sm:text-xs text-white font-semibold">{{ \Carbon\Carbon::parse($p->mulai_sewa)->format('d M Y') }}</span>
                                 </div>
                                 <div>
                                     <span class="block text-[9px] sm:text-[10px] text-zinc-500 font-bold uppercase mb-0.5">Akhir:</span>
                                     <span class="text-[11px] sm:text-xs text-zinc-300 font-medium">{{ \Carbon\Carbon::parse($p->selesai_sewa)->format('d M Y') }}</span>
                                 </div>
                                 <div class="col-span-2">
                                     <span class="block text-[9px] sm:text-[10px] text-zinc-500 font-bold uppercase mb-0.5">Durasi Sewa:</span>
                                     <span class="text-[11px] sm:text-xs text-emerald-400 font-bold">{{ $p->durasi_bulan }} Bulan</span>
                                 </div>
                             </div>
                        </div>

                        <button type="button"
                            onclick="bukaModalHarga(this)"
                            data-id="{{ $p->id }}"
                            data-klien="{{ $p->user?->nama_perusahaan ?: ($p->user?->name ?? 'Klien Dihapus') }}"
                            data-mulai="{{ \Carbon\Carbon::parse($p->mulai_sewa)->format('d M Y') }}"
                            data-selesai="{{ \Carbon\Carbon::parse($p->selesai_sewa)->format('d M Y') }}"
                            data-durasi="{{ $p->durasi_bulan }}"
                            data-pic="{{ $p->user?->name ?? '-' }}"
                            data-wa="{{ $p->user?->no_wa ?? '' }}"
                            data-nomor="{{ $p->nomor_pengajuan ?? '-' }}"
                            data-rincian="{{ json_encode($p->rincian_lengkap) }}"
                            class="w-full flex items-center justify-center bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-3 sm:py-3.5 rounded-xl text-xs sm:text-sm font-black transition shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                            Input Harga Final &rarr;
                        </button>
                    </div>
                @empty
                    <div class="px-6 py-12 flex flex-col items-center justify-center text-zinc-500 bg-zinc-900/30 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-xs sm:text-sm">Belum ada data yang menunggu penawaran harga.</p>
                    </div>
                @endforelse
            </div>

            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="text-[10px] text-zinc-500 uppercase font-black bg-zinc-900/80 tracking-widest border-b border-zinc-800">
                        <tr>
                            <th class="px-5 xl:px-6 py-4">Perusahaan</th>
                            <th class="px-5 xl:px-6 py-4">Kontak PIC</th>
                            <th class="px-5 xl:px-6 py-4">Detail Titik Sewa</th>
                            <th class="px-5 xl:px-6 py-4">Jadwal Pengajuan</th>
                            <th class="px-5 xl:px-6 py-4 text-center">Status</th>
                            <th class="px-5 xl:px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/60">
                        @forelse ($penawaran_list as $p)
                            @php
                                $rincianData = json_decode(json_encode($p->rincian_lengkap), true);
                                $titikList = $rincianData['titik'] ?? [];
                            @endphp
                            <tr class="hover:bg-zinc-800/30 transition duration-200">
                                
                                <td class="px-5 xl:px-6 py-5 align-top">
                                    <div class="flex items-start gap-4">
                                        <div class="h-10 w-10 shrink-0 items-center justify-center rounded-full bg-zinc-800 border border-zinc-700 text-white font-black text-sm hidden xl:flex">
                                            {{ strtoupper(substr($p->user?->nama_perusahaan ?: ($p->user?->name ?? 'K'), 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-white mb-1 text-sm truncate">{{ $p->user?->nama_perusahaan ?: ($p->user?->name ?? 'Klien Dihapus') }}</div>
                                            <div class="text-[10px] text-zinc-500 font-medium">{{ $p->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-5 xl:px-6 py-5 align-top">
                                    <div class="font-medium text-zinc-300 text-xs mb-1.5 truncate">{{ $p->user->name ?? '-' }}</div>
                                    <div class="text-xs text-emerald-500 font-mono font-bold flex items-center truncate">
                                        <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $p->user?->no_wa ?? '-' }}
                                    </div>
                                </td>
                                
                                <td class="px-5 xl:px-6 py-5 align-top min-w-[250px]">
                                    <div class="space-y-3">
                                        @foreach ($titikList as $t)
                                            <div class="bg-zinc-900 border border-zinc-800 p-3 rounded-lg">
                                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                                    <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded text-[10px] font-bold font-mono">{{ $t['kode'] ?? '-' }}</span>
                                                    <span class="text-[10px] bg-zinc-800 text-zinc-300 px-2 py-0.5 rounded font-medium">{{ $t['ukuran'] ?? '-' }}</span>
                                                </div>
                                                <p class="text-xs text-white leading-relaxed font-medium mb-1">{{ $t['lokasi'] ?? 'Data lokasi tidak ditemukan' }}</p>
                                                <p class="text-[9px] text-zinc-500 uppercase tracking-wider">{{ $t['kab_kota'] ?? '-' }} &middot; {{ $t['jenis_ooh'] ?? '-' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($p->butuh_jasa_desain)
                                        <div class="mt-3 text-[9px] text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20 inline-block px-2.5 py-1 rounded-md">+ Jasa Desain BOMA</div>
                                    @endif
                                </td>
                                
                                <td class="px-5 xl:px-6 py-5 align-top whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center text-xs">
                                            <span class="text-[10px] text-zinc-500 font-bold uppercase w-12">Mulai:</span>
                                            <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($p->mulai_sewa)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span class="text-[10px] text-zinc-500 font-bold uppercase w-12">Akhir:</span>
                                            <span class="text-zinc-400">{{ \Carbon\Carbon::parse($p->selesai_sewa)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center text-xs mt-1">
                                            <span class="text-[10px] text-zinc-500 font-bold uppercase w-12">Durasi:</span>
                                            <span class="text-emerald-400 font-bold">{{ $p->durasi_bulan }} Bulan</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-5 xl:px-6 py-5 text-center align-top">
                                    <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1.5 rounded-full text-[10px] font-bold shadow-sm inline-block">
                                        {{ strtoupper($p->status_pengajuan) }}
                                    </span>
                                </td>
                                
                                <td class="px-5 xl:px-6 py-5 text-right align-top">
                                    <button type="button" 
                                        onclick="bukaModalHarga(this)"
                                        data-id="{{ $p->id }}"
                                        data-klien="{{ $p->user?->nama_perusahaan ?: ($p->user?->name ?? 'Klien Dihapus') }}"
                                        data-mulai="{{ \Carbon\Carbon::parse($p->mulai_sewa)->format('d M Y') }}"
                                        data-selesai="{{ \Carbon\Carbon::parse($p->selesai_sewa)->format('d M Y') }}"
                                        data-durasi="{{ $p->durasi_bulan }}"
                                        data-pic="{{ $p->user?->name ?? '-' }}"
                                        data-wa="{{ $p->user?->no_wa ?? '' }}"
                                        data-nomor="{{ $p->nomor_pengajuan ?? '-' }}"
                                        data-rincian="{{ json_encode($p->rincian_lengkap) }}"
                                        class="inline-flex items-center bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-xs font-black transition shadow-[0_0_15px_rgba(16,185,129,0.2)] hover:-translate-y-0.5 whitespace-nowrap">
                                        Input Harga &rarr;
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-zinc-500 bg-zinc-900/30">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    Belum ada data yang menunggu penawaran harga.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-harga" class="fixed inset-0 z-[99999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-6 lg:p-8">
                <div id="modal-content-harga" class="relative w-full max-w-6xl transform overflow-hidden rounded-2xl border border-zinc-700 bg-zinc-900 text-left shadow-2xl transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 flex flex-col">
                    
                    <div class="flex-none px-4 sm:px-6 py-4 border-b border-zinc-800 flex justify-between items-center bg-zinc-950 sticky top-0 z-20">
                        <h3 class="text-base sm:text-lg font-black text-white flex items-center truncate">
                            <span class="bg-emerald-500/20 text-emerald-500 p-1.5 rounded-lg mr-2 sm:mr-3 shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <span class="truncate">Penetapan Harga & Rincian</span>
                        </h3>
                        <button type="button" onclick="tutupModalHarga()" class="text-zinc-500 hover:text-white bg-zinc-800 hover:bg-red-500 p-1.5 sm:p-2 rounded-full transition shrink-0 ml-2 focus:outline-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form id="form-harga" method="POST" action="" class="flex flex-col flex-1">
                        @csrf
                        <div class="p-4 sm:p-6 bg-zinc-900">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
                                
                                <div class="lg:col-span-4 space-y-4">
                                    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 sm:p-5 shadow-inner sticky top-6">
                                        <h4 class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-3 flex items-center border-b border-zinc-800 pb-2">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Data Pengajuan
                                        </h4>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <p class="text-[10px] text-zinc-500 mb-0.5">No. Pengajuan</p>
                                                <p class="text-zinc-300 font-mono font-bold text-xs" id="mh-nomor">-</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-zinc-500 mb-0.5">Perusahaan / Klien</p>
                                                <p class="text-white font-bold text-sm" id="mh-klien">-</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-zinc-500 mb-0.5">Kontak PIC (WhatsApp)</p>
                                                <p class="text-zinc-300 font-medium text-xs" id="mh-pic">-</p>
                                                <p class="text-emerald-400 font-mono font-bold text-sm mt-0.5" id="mh-wa">-</p>
                                                <a href="#" target="_blank" id="mh-wa-link" class="mt-2 hidden w-full bg-emerald-600/15 hover:bg-emerald-600/30 text-emerald-400 border border-emerald-500/30 px-3 py-2 rounded-lg text-[11px] font-bold transition items-center justify-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            Hubungi via WhatsApp
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-zinc-500 mb-0.5">Estimasi Sistem Asli</p>
                                        <p class="text-emerald-400 font-black text-sm" id="mh-estimasi">-</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-zinc-500 mb-0.5">Masa Sewa</p>
                                        <p class="text-zinc-300 font-medium text-xs bg-zinc-900 px-2 py-1.5 rounded border border-zinc-800 inline-block" id="mh-jadwal">-</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-zinc-500 mb-2">Titik Reklame Diminta</p>
                                        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-3 space-y-3" id="mh-titik">
                                            -
                                        </div>
                                    </div>
                                </div>

                                <div id="mh-alert-jasa" class="hidden mt-4 bg-blue-500/10 border border-blue-500/20 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-[10px] text-blue-400 font-medium leading-relaxed">Klien meminta <b>Jasa Desain BOMA</b>. Silakan tentukan biaya tambahan pada rincian di samping.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-8 space-y-4 sm:space-y-6 flex flex-col">
                            
                            <div class="bg-zinc-950 border border-zinc-800 p-4 sm:p-5 rounded-xl shrink-0">
                                <label class="block text-sm font-bold text-white mb-1.5">Total Harga Deal (Rp) *</label>
                                <p class="text-[10px] text-zinc-500 mb-3 sm:mb-4 leading-relaxed">Dihitung otomatis dari Rincian Komponen Biaya di bawah. Untuk diskon, tambahkan komponen <b class="text-orange-400">Diskon Negosiasi</b> (bernilai minus).</p>
                                
                                <div class="relative">
                                    <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-emerald-500 font-black text-lg sm:text-xl">Rp</span>
                                    <input type="text" id="input-harga-mask" readonly tabindex="-1" placeholder="0" class="w-full bg-zinc-900/60 border border-zinc-800 text-emerald-400 text-lg sm:text-2xl font-black rounded-xl pl-10 sm:pl-14 pr-4 py-3 sm:py-4 outline-none transition shadow-inner cursor-not-allowed">
                                    <input type="hidden" name="harga_final" id="input-harga-real">
                                </div>
                                <div id="terbilang" class="mt-3 text-[10px] sm:text-xs font-bold text-emerald-500 bg-emerald-500/10 px-3 sm:px-4 py-2.5 rounded-lg hidden flex items-center border border-emerald-500/20">
                                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span id="terbilang-text">Nol Rupiah</span>
                                </div>
                            </div>

                            <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 sm:p-5 flex-1 flex flex-col min-h-0">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 pb-3 border-b border-zinc-800 shrink-0">
                                    <div>
                                        <label class="block text-xs font-bold text-zinc-300">Rincian Komponen Biaya (Transparansi) *</label>
                                        <p class="text-[10px] text-zinc-500 mt-1">Sistem mengambil data dari master harga. Anda bisa menyesuaikannya.</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2 w-full sm:w-auto shrink-0">
                                        <button type="button" onclick="tambahBarisRincian()" class="flex-1 sm:flex-none justify-center text-[10px] sm:text-xs bg-zinc-800 hover:bg-zinc-700 text-white px-3 py-2 rounded-lg border border-zinc-700 transition flex items-center shadow-sm focus:outline-none">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah
                                        </button>
                                        <button type="button" onclick="tambahBarisDiskon()" class="flex-1 sm:flex-none justify-center text-[10px] sm:text-xs bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 px-3 py-2 rounded-lg border border-orange-500/30 transition flex items-center shadow-sm focus:outline-none">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg> Diskon
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="wadah-rincian" class="space-y-3"></div>

                                <div id="status-pencocokan" class="mt-4 sm:mt-5 p-3 sm:p-3.5 rounded-xl border text-[10px] sm:text-xs font-bold hidden transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 shrink-0">
                                    <span id="pesan-selisih">-</span>
                                    <span id="total-hitung-rincian" class="font-mono text-xs sm:text-sm shrink-0">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-none px-4 sm:px-6 py-4 border-t border-zinc-800 bg-zinc-950 flex flex-col sm:flex-row justify-end gap-3 sticky bottom-0 z-20">
                    <button type="button" onclick="tutupModalHarga()" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-zinc-400 hover:text-white hover:bg-zinc-800 border border-transparent hover:border-zinc-700 rounded-lg transition focus:outline-none">Batal</button>
                    <button type="submit" id="btn-submit-harga" disabled class="w-full sm:w-auto px-8 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-bold transition flex justify-center items-center shadow-[0_0_15px_rgba(16,185,129,0.3)] focus:outline-none">
                        Simpan & Terbitkan Harga &rarr;
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
        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById('modal-harga');
            if(modal) {
                document.body.appendChild(modal);
            }
        });

        function formatRupiah(angka) {
            if (angka === 0) return '0';
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        function penyebut(nilai) {
            nilai = Math.abs(nilai);
            var huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            var temp = "";
            if (nilai < 12) {
                temp = " " + huruf[nilai];
            } else if (nilai < 20) {
                temp = penyebut(nilai - 10) + " Belas";
            } else if (nilai < 100) {
                temp = penyebut(Math.floor(nilai / 10)) + " Puluh" + penyebut(nilai % 10);
            } else if (nilai < 200) {
                temp = " Seratus" + penyebut(nilai - 100);
            } else if (nilai < 1000) {
                temp = penyebut(Math.floor(nilai / 100)) + " Ratus" + penyebut(nilai % 100);
            } else if (nilai < 2000) {
                temp = " Seribu" + penyebut(nilai - 1000);
            } else if (nilai < 1000000) {
                temp = penyebut(Math.floor(nilai / 1000)) + " Ribu" + penyebut(nilai % 1000);
            } else if (nilai < 1000000000) {
                temp = penyebut(Math.floor(nilai / 1000000)) + " Juta" + penyebut(nilai % 1000000);
            } else if (nilai < 1000000000000) {
                temp = penyebut(Math.floor(nilai / 1000000000)) + " Miliar" + penyebut(nilai % 1000000000);
            }
            return temp;
        }

        function terbilang(nilai) {
            if (nilai == 0) return "Nol Rupiah";
            let hasil = penyebut(nilai).trim();
            return hasil + " Rupiah";
        }

        function hitungTotalOtomatis() {
            let total = 0;
            let hiddenInputs = document.querySelectorAll('input[name="rincian_harga[]"]');
            hiddenInputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });

            let inputMask = document.getElementById('input-harga-mask');
            let inputReal = document.getElementById('input-harga-real');
            let terbilangBox = document.getElementById('terbilang');

            inputReal.value = total;
            inputMask.value = total !== 0 ? formatRupiah(total) : '';

            if (total > 0) {
                terbilangBox.classList.remove('hidden');
                document.getElementById('terbilang-text').innerText = terbilang(total);
            } else {
                terbilangBox.classList.add('hidden');
            }

            let boxStatus = document.getElementById('status-pencocokan');
            let pesanSelisih = document.getElementById('pesan-selisih');
            let totalHitungEl = document.getElementById('total-hitung-rincian');
            let btnSubmit = document.getElementById('btn-submit-harga');

            boxStatus.classList.remove('hidden');
            totalHitungEl.innerText = 'Rp ' + formatRupiah(total);

            if (total <= 0) {
                boxStatus.className = "mt-4 sm:mt-5 p-3 sm:p-3.5 rounded-xl border text-[10px] sm:text-xs font-bold bg-zinc-800 text-zinc-400 border-zinc-700 flex flex-col sm:flex-row gap-2 sm:items-center justify-between transition-all shrink-0";
                pesanSelisih.innerText = "\u26a0\ufe0f Isi minimal satu komponen biaya dengan nilai > 0.";
                btnSubmit.disabled = true;
                btnSubmit.className = "w-full sm:w-auto px-8 py-2.5 bg-zinc-700 text-zinc-500 rounded-lg text-sm font-bold transition cursor-not-allowed flex justify-center items-center focus:outline-none";
            } else {
                boxStatus.className = "mt-4 sm:mt-5 p-3 sm:p-3.5 rounded-xl border text-[10px] sm:text-xs font-bold bg-emerald-500/10 text-emerald-400 border-emerald-500/30 flex flex-col sm:flex-row gap-2 sm:items-center justify-between transition-all shrink-0";
                pesanSelisih.innerText = "\u2705 Total dihitung otomatis dari " + hiddenInputs.length + " baris rincian.";
                btnSubmit.disabled = false;
                btnSubmit.className = "w-full sm:w-auto px-8 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-lg transition shadow-[0_0_20px_rgba(16,185,129,0.4)] flex justify-center items-center focus:outline-none";
            }
        }

        function cekPencocokanHarga() {
            hitungTotalOtomatis();
        }

        function maskInputRincian(input) {
            let raw = input.value.replace(/[^0-9-]/g, '');
            let angka = raw.replace(/-/g, '');
            let hiddenInput = input.nextElementSibling;
            let wajibMinus = input.dataset.diskon === '1';
            let negatif = wajibMinus || raw.charAt(0) === '-';

            if (angka !== '' && parseInt(angka) !== 0) {
                hiddenInput.value = (negatif ? '-' : '') + angka;
                input.value = (negatif ? '-' : '') + formatRupiah(parseInt(angka));
            } else if (angka !== '') {
                hiddenInput.value = '0';
                input.value = '0';
            } else {
                hiddenInput.value = '';
                input.value = '';
            }
            cekPencocokanHarga();
        }

        function tambahBarisDiskon() {
            tambahBarisRincian('Diskon Negosiasi', '', true);
        }

        function tambahBarisRincian(namaDefault = '', hargaDefault = '', isDiskon = false) {
            let wadah = document.getElementById('wadah-rincian');
            let div = document.createElement('div');
            div.className = 'flex flex-col sm:flex-row gap-2 sm:gap-3 items-center group relative bg-zinc-950 border p-2.5 sm:p-2 rounded-lg shrink-0 ' + (isDiskon ? 'border-orange-500/30' : 'border-zinc-800');
            
            let formattedHarga = '';
            if (hargaDefault !== '') {
               formattedHarga = formatRupiah(hargaDefault);
            }
            let rawHarga = hargaDefault !== '' ? hargaDefault : '';

            div.innerHTML = `
                <input type="text" name="rincian_nama[]" value="${namaDefault}" placeholder="Nama Komponen Biaya" required class="w-full sm:flex-1 bg-zinc-900 border border-zinc-700 text-white text-xs rounded-lg px-3 py-2.5 focus:border-emerald-500 outline-none transition">
                <div class="relative w-full sm:w-48 md:w-56 flex items-center shrink-0">
                    <span class="absolute left-3 text-zinc-500 text-xs font-bold">Rp</span>
                    <input type="text" value="${formattedHarga}" required placeholder="${isDiskon ? '-0' : '0'}" ${isDiskon ? 'data-diskon="1"' : ''} class="w-full bg-zinc-900 border ${isDiskon ? 'border-orange-500/40 text-orange-400 focus:border-orange-500' : 'border-zinc-700 text-white focus:border-emerald-500'} text-xs font-bold rounded-lg pl-8 pr-10 py-2.5 outline-none transition" oninput="maskInputRincian(this)">
                    <input type="hidden" name="rincian_harga[]" value="${rawHarga}">
                    <button type="button" onclick="this.parentElement.parentElement.remove(); cekPencocokanHarga();" class="absolute right-2 text-red-500 hover:text-red-400 bg-zinc-800 hover:bg-zinc-700 p-1.5 rounded-md transition focus:outline-none" title="Hapus Baris">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            `;
            wadah.appendChild(div);
            cekPencocokanHarga();
        }

        function bukaModalHarga(btn) {
            let id = btn.getAttribute('data-id');
            let klien = btn.getAttribute('data-klien');
            let pic = btn.getAttribute('data-pic') || '-';
            let wa = btn.getAttribute('data-wa') || '';
            let nomor = btn.getAttribute('data-nomor') || '-';
            let mulai = btn.getAttribute('data-mulai');
            let selesai = btn.getAttribute('data-selesai');
            let durasi = btn.getAttribute('data-durasi');
            let rincianData = JSON.parse(btn.getAttribute('data-rincian'));
            
            let displayTitikHtml = rincianData.titik.map(item => `
                <div class="border-b border-zinc-800 pb-3 last:border-0 last:pb-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                        <span class="bg-red-500/10 text-red-400 px-2 py-0.5 rounded text-[10px] font-bold font-mono tracking-wide border border-red-500/20">${item.kode}</span>
                        <span class="text-[10px] font-medium text-zinc-300 bg-zinc-800 px-2 py-0.5 rounded">${item.ukuran}</span>
                    </div>
                    <p class="text-[11px] sm:text-xs text-white leading-relaxed font-medium mb-1">${item.lokasi ?? '-'}</p>
                    <p class="text-[9px] sm:text-[10px] text-zinc-500 uppercase tracking-wider">${item.kab_kota ?? '-'} &middot; ${item.jenis_ooh ?? '-'}</p>
                </div>
            `).join('');

            document.getElementById('mh-klien').innerText = klien;
            document.getElementById('mh-nomor').innerText = nomor;
            document.getElementById('mh-pic').innerText = pic;

            let waEl = document.getElementById('mh-wa');
            let waLink = document.getElementById('mh-wa-link');
            let waBersih = wa.replace(/[^0-9]/g, '');
            if (waBersih.charAt(0) === '0') {
                waBersih = '62' + waBersih.substring(1);
            }

            if (waBersih.length >= 9) {
                waEl.innerText = wa;
                waLink.href = 'https://wa.me/' + waBersih;
                waLink.classList.remove('hidden');
                waLink.classList.add('flex');
            } else {
                waEl.innerText = 'Nomor WA belum diisi';
                waLink.classList.add('hidden');
                waLink.classList.remove('flex');
            }
            
            document.getElementById('mh-titik').innerHTML = displayTitikHtml;
            document.getElementById('mh-jadwal').innerText = mulai + ' - ' + selesai + ' (' + durasi + ' Bln)';
            document.getElementById('mh-estimasi').innerText = 'Rp ' + formatRupiah(rincianData.total_estimasi);

            let alertJasa = document.getElementById('mh-alert-jasa');
            if (rincianData.butuh_jasa) {
                alertJasa.classList.remove('hidden');
            } else {
                alertJasa.classList.add('hidden');
            }

            document.getElementById('form-harga').action = '/admin/penawaran/' + id + '/deal';

            let wadahRincian = document.getElementById('wadah-rincian');
            wadahRincian.innerHTML = ''; 
            
            rincianData.titik.forEach(item => {
                tambahBarisRincian('Sewa Reklame ' + item.kode + ' (' + item.ukuran + ')', item.harga_asli);
            });

            tambahBarisRincian('Pajak Reklame (PB 1) 11%', rincianData.pajak);

            if (rincianData.butuh_jasa) {
                tambahBarisRincian('Biaya Jasa Desain Visual', 0); 
            }

            cekPencocokanHarga();

            let modal = document.getElementById('modal-harga');
            let backdrop = document.getElementById('modal-backdrop');
            let content = document.getElementById('modal-content-harga');
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                content.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 20);
        }

        function tutupModalHarga() {
            let modal = document.getElementById('modal-harga');
            let backdrop = document.getElementById('modal-backdrop');
            let content = document.getElementById('modal-content-harga');
            
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            content.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    </script>
</x-boma-layout>