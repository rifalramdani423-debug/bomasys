<x-boma-layout>
    <div class="pb-10 flex flex-col space-y-6" x-data="{ 
        modalEditDokumen: false, 
        activeDokumen: {}, 
        jenisDokumen: '',
        kategoriPesanan: ['Bukti Pembayaran', 'Invoice', 'Kwitansi', 'SKPD', 'BAST', 'Purchase Order', 'Lainnya']
    }">
        
        <!-- ========================================================= -->
        <!-- 1. TAMPILAN DETAIL DOKUMEN & FOLDER (MUNCUL JIKA ADA ID)  -->
        <!-- ========================================================= -->
        @if(isset($klienDetail))

            @php
                $arsip_dokumen = $klienDetail->dokumens;
                $list_pengajuan = $klienDetail->pengajuan;
                $materiVisualList = $klienDetail->pengajuan;
                
                $namaKlien = $klienDetail->nama_perusahaan ?: $klienDetail->name;
                $laporanTayang = \Illuminate\Support\Facades\DB::table('laporan_qcs')
                    ->join('internal_memos', 'laporan_qcs.no_im', '=', 'internal_memos.no_im')
                    ->select('laporan_qcs.*', 'internal_memos.klien_visual', 'internal_memos.perihal')
                    ->where('laporan_qcs.dibagikan_ke_klien', 1)
                    ->where('internal_memos.klien_visual', 'LIKE', '%' . $namaKlien . '%')
                    ->orderBy('laporan_qcs.created_at', 'desc')
                    ->get();

                $jenisGlobal = ['KTP', 'NPWP', 'Surat Izin Perusahaan'];
                $dokumenProfil = $arsip_dokumen->whereIn('jenis_dokumen', $jenisGlobal)->whereNull('pengajuan_id');
                $role = auth()->user()->role;
            @endphp

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-2">
                <div>
                    <a href="{{ route('keuangan.klien') }}" class="inline-flex items-center text-xs font-bold text-zinc-400 hover:text-white transition mb-4 bg-zinc-900 border border-zinc-800 px-3 py-1.5 rounded-lg">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Data Finansial
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Arsip: {{ $klienDetail->nama_perusahaan ?? $klienDetail->name }} 📂</h1>
                    <p class="text-sm text-zinc-400 mt-1">Pusat arsip tagihan, invoice, dan folder pesanan klien.</p>
                </div>
                <div class="flex gap-3">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $klienDetail->no_wa ?? '') }}" target="_blank" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-[0_0_15px_rgba(16,185,129,0.4)] shrink-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Hubungi Klien
                    </a>
                </div>
            </div>

            <!-- KARTU INFORMASI PROFIL KLIEN -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl flex flex-col md:flex-row items-center md:justify-start justify-center gap-6 relative overflow-hidden mt-2">
                <div class="absolute right-0 top-0 w-64 h-64 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none -mr-10 -mt-10"></div>
                
                <div class="w-20 h-20 bg-zinc-950 border-2 border-zinc-800 rounded-full flex items-center justify-center text-3xl shrink-0 shadow-inner z-10">
                    🏢
                </div>
                
                <div class="z-10 text-center md:text-left flex flex-col justify-center">
                    <h2 class="text-xl md:text-2xl font-black text-white mb-1.5">{{ $klienDetail->nama_perusahaan ?? $klienDetail->name }}</h2>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 md:gap-6 text-sm text-zinc-400">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            PIC: {{ $klienDetail->name }}
                        </span>
                        <span class="flex items-center text-emerald-400 font-mono font-bold">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $klienDetail->no_wa ?? 'Belum ada WA' }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $klienDetail->email }}
                        </span>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- ROW ATAS SEJAJAR: FORM UPLOAD & PROFIL IDENTITAS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-4 mt-6">
                
                <!-- KOLOM KIRI: FORM UPLOAD KHUSUS KEUANGAN -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-zinc-800 bg-zinc-950/50 shrink-0">
                        <h3 class="text-md font-bold text-white flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Unggah Tagihan / Bukti
                        </h3>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-center">
                        <form action="{{ route('keuangan.dokumen.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $klienDetail->id }}">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 mb-2 uppercase tracking-widest">Kategori Berkas *</label>
                                    <div class="relative">
                                        <select name="jenis_dokumen" x-model="jenisDokumen" required class="appearance-none w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition cursor-pointer shadow-inner">
                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                            <option value="Invoice">Invoice / Tagihan Resmi</option>
                                            <option value="Kwitansi">Kwitansi Pembayaran Lunas</option>
                                            <option value="Lainnya">Lainnya (Ketik Manual)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-zinc-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="jenisDokumen === 'Lainnya'" style="display: none;" x-transition>
                                    <label class="block text-[10px] font-bold text-zinc-400 mb-2 uppercase tracking-widest">Nama Dokumen Kustom *</label>
                                    <input type="text" name="jenis_dokumen_custom" placeholder="Contoh: Bukti Refund" :required="jenisDokumen === 'Lainnya'" class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition shadow-inner">
                                </div>

                                <div x-show="kategoriPesanan.includes(jenisDokumen)" style="display: none;" x-transition>
                                    <label class="block text-[10px] font-bold text-yellow-500 mb-2 uppercase tracking-widest">Terkait Pesanan Mana? *</label>
                                    <div class="relative">
                                        <select name="pengajuan_id" :required="kategoriPesanan.includes(jenisDokumen)" class="appearance-none w-full bg-zinc-950 border border-yellow-500/50 text-white text-sm rounded-xl px-4 py-3 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 outline-none transition cursor-pointer shadow-inner">
                                            <option value="" disabled selected>-- Pilih ID Pesanan --</option>
                                            @foreach($list_pengajuan ?? [] as $pj)
                                                <option value="{{ $pj->id }}">{{ $pj->nomor_pengajuan }} (Rp {{ number_format($pj->harga_final ?? $pj->estimasi_harga, 0, ',', '.') }})</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-yellow-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 mb-2 uppercase tracking-widest">File Dokumen (PDF/JPG/PNG) *</label>
                                    <input type="file" name="file_dokumen" required accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-zinc-950 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-4 py-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700 transition cursor-pointer shadow-inner">
                                </div>
                            </div>

                            <div class="mt-auto pt-6">
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition text-sm md:text-base shadow-[0_0_20px_rgba(16,185,129,0.35)] flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Unggah & Simpan Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- KOLOM KANAN: ARSIP PROFIL IDENTITAS -->
                <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-zinc-800 bg-zinc-950/50 shrink-0 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <h3 class="text-md font-bold text-white">Arsip Profil Identitas (KTP & NPWP)</h3>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left text-sm text-zinc-400 h-full">
                            <thead class="text-[10px] text-zinc-500 uppercase bg-zinc-950/80 tracking-widest border-b border-zinc-800">
                                <tr>
                                    <th class="px-6 py-4 font-bold">Jenis Berkas</th>
                                    <th class="px-6 py-4 font-bold">Waktu Unggah</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/50">
                                @forelse ($dokumenProfil as $dok)
                                    <tr class="hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-4 font-bold text-white">{{ $dok->jenis_dokumen }}</td>
                                        <td class="px-6 py-4 text-xs">{{ $dok->created_at->format('d M Y - H:i') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($dok->status == 'Menunggu Validasi')
                                                <span class="inline-block bg-orange-500/10 text-orange-400 px-2 py-0.5 rounded text-[9px] font-bold uppercase border border-orange-500/20">Pending</span>
                                            @else
                                                <span class="inline-block bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded text-[9px] font-bold uppercase border border-emerald-500/20">ACC</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="inline-block text-[10px] font-bold text-blue-400 bg-blue-500/10 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg border border-blue-500/30 transition">Buka</a>
                                            <!-- Catatan: Keuangan DILARANG mengedit KTP/NPWP klien, jadi tombol Edit dihilangkan di sini -->
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-xs text-zinc-500">Berkas identitas utama belum diunggah Klien.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- FULL WIDTH: FOLDER ARSIP BERDASARKAN PESANAN -->
            <div class="mt-8">
                <h2 class="text-lg font-black text-white flex items-center mb-6">
                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    Folder Berkas Berdasarkan Pesanan
                </h2>

                <div class="space-y-8">
                    @forelse($list_pengajuan ?? [] as $pesanan)
                        @php
                            $docsPesanan = $arsip_dokumen->where('pengajuan_id', $pesanan->id);
                            $dokKeuangan = $docsPesanan->whereIn('jenis_dokumen', ['Invoice', 'Kwitansi', 'Bukti Pembayaran']);
                            $dokAdmin    = $docsPesanan->whereNotIn('jenis_dokumen', ['Invoice', 'Kwitansi', 'Bukti Pembayaran']);
                            $visual      = $materiVisualList->where('id', $pesanan->id)->first();
                            
                            $titikPesanan = $pesanan->details->pluck('kode_titik')->toArray();
                            $laporanPesananIni = collect($laporanTayang ?? [])->filter(function($t) use ($titikPesanan) {
                                $tArray = json_decode($t->kode_titik, true);
                                if(is_array($tArray)) return count(array_intersect($tArray, $titikPesanan)) > 0;
                                return in_array($t->kode_titik, $titikPesanan);
                            });
                        @endphp
                        
                        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl shadow-xl overflow-hidden">
                            
                            <div class="bg-zinc-950/80 px-6 py-4 border-b border-zinc-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h3 class="font-black text-white text-lg flex items-center">
                                        Pesanan {{ $pesanan->nomor_pengajuan }}
                                    </h3>
                                    <p class="text-xs text-zinc-400 font-mono mt-0.5">Nilai Kesepakatan: <span class="text-emerald-400 font-bold">Rp {{ number_format($pesanan->harga_final ?? $pesanan->estimasi_harga, 0, ',', '.') }}</span></p>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-lg {{ $pesanan->status_pengajuan === 'Lunas / Aktif' || $pesanan->status_pengajuan === 'Selesai' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                                    Status: {{ $pesanan->status_pengajuan }}
                                </span>
                            </div>

                            <!-- 4 KOLOM DIVISI TERPISAH -->
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                
                                <!-- 1. KOLOM KLIEN (VISUAL) -->
                                <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 flex flex-col">
                                    <h4 class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-3 flex items-center pb-2 border-b border-zinc-800">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div> Upload Klien (Visual)
                                    </h4>
                                    
                                    <div class="flex-1 flex items-center justify-center">
                                        @if($visual && $visual->jasa_desain)
                                            <div class="bg-purple-500/10 border border-purple-500/30 text-purple-400 px-3 py-2 rounded-lg text-xs font-bold text-center w-full">
                                                Klien Meminta Jasa Desain
                                            </div>
                                        @elseif($visual && $visual->file_preview)
                                            <a href="{{ asset('storage/' . $visual->file_preview) }}" target="_blank" class="block w-full h-24 rounded-lg border border-zinc-700 overflow-hidden relative group shadow-md bg-zinc-900">
                                                <img src="{{ asset('storage/' . $visual->file_preview) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                    <span class="text-[10px] font-bold text-white border border-white/50 px-3 py-1 rounded">Lihat Penuh</span>
                                                </div>
                                            </a>
                                        @elseif($visual && $visual->link_desain)
                                            <a href="{{ $visual->link_desain }}" target="_blank" class="w-full h-24 rounded-lg border border-dashed border-zinc-700 flex flex-col items-center justify-center bg-zinc-900 hover:bg-purple-500/10 hover:border-purple-500/50 transition group">
                                                <svg class="w-6 h-6 text-zinc-600 group-hover:text-purple-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                <span class="text-xs font-bold text-zinc-500 group-hover:text-purple-400">Buka Link Drive</span>
                                            </a>
                                        @else
                                            <div class="text-[10px] text-zinc-600 italic text-center">Belum ada materi visual naskah.</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- 2. KOLOM KEUANGAN -->
                                <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 flex flex-col">
                                    <h4 class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-3 flex items-center pb-2 border-b border-zinc-800">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div> Upload Keuangan
                                    </h4>
                                    
                                    <div class="space-y-2.5 flex-1">
                                        @forelse($dokKeuangan as $dok)
                                            <div class="bg-zinc-900 border border-zinc-800 p-2 rounded-lg flex items-center justify-between">
                                                <div class="w-2/3 pr-2">
                                                    <p class="text-[10px] font-bold text-white leading-tight truncate" title="{{ $dok->jenis_dokumen }}">{{ $dok->jenis_dokumen }}</p>
                                                    <p class="text-[8px] text-zinc-500 mt-0.5">{{ $dok->created_at->format('d/m/y H:i') }}</p>
                                                </div>
                                                <div class="flex flex-col gap-1 w-1/3 shrink-0">
                                                    <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="text-[8px] font-bold text-center text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded hover:bg-emerald-500 hover:text-white transition w-full">Buka</a>
                                                    
                                                    <!-- Tim Keuangan berhak edit invoice/tagihannya -->
                                                    @if($role === 'superadmin' || $role === 'keuangan' || $role === 'finance')
                                                        <button @click="activeDokumen = {{ json_encode($dok) }}; modalEditDokumen = true" class="text-[8px] font-bold text-center text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded hover:bg-zinc-700 hover:text-white transition w-full">Edit</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="flex items-center justify-center h-full text-[10px] text-zinc-600 italic">Belum ada tagihan / bayar.</div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- 3. KOLOM ADMIN (LEGAL) -->
                                <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 flex flex-col">
                                    <h4 class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-3 flex items-center pb-2 border-b border-zinc-800">
                                        <div class="w-2 h-2 bg-amber-500 rounded-full mr-2"></div> Upload Admin (Legal)
                                    </h4>
                                    
                                    <div class="space-y-2.5 flex-1">
                                        @forelse($dokAdmin as $dok)
                                            <div class="bg-zinc-900 border border-zinc-800 p-2 rounded-lg flex items-center justify-between">
                                                <div class="w-2/3 pr-2">
                                                    <p class="text-[10px] font-bold text-white leading-tight truncate" title="{{ $dok->jenis_dokumen }}">{{ $dok->jenis_dokumen }}</p>
                                                    <p class="text-[8px] text-zinc-500 mt-0.5">{{ $dok->created_at->format('d/m/y H:i') }}</p>
                                                </div>
                                                <div class="flex flex-col gap-1 w-1/3 shrink-0">
                                                    <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="text-[8px] font-bold text-center text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded hover:bg-amber-500 hover:text-white transition w-full">Buka</a>
                                                    <!-- Keuangan TIDAK BISA edit berkas Admin -->
                                                </div>
                                            </div>
                                        @empty
                                            <div class="flex items-center justify-center h-full text-[10px] text-zinc-600 italic">Belum ada berkas legal.</div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- 4. KOLOM TIM ASET (FOTO TAYANG) -->
                                <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 flex flex-col">
                                    <h4 class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-3 flex items-center pb-2 border-b border-zinc-800">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div> Upload Aset (Tayang)
                                    </h4>
                                    
                                    <div class="flex-1 overflow-y-auto">
                                        @if($laporanPesananIni->isNotEmpty())
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($laporanPesananIni as $tayang)
                                                    @php $fotoKlien = json_decode($tayang->foto_klien, true) ?? []; @endphp
                                                    @if(count($fotoKlien) > 0)
                                                        <a href="{{ asset('storage/' . $fotoKlien[0]) }}" target="_blank" class="block w-full h-16 rounded-lg border border-zinc-700 overflow-hidden relative group">
                                                            <img src="{{ asset('storage/' . $fotoKlien[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                                                <span class="text-[8px] font-bold text-white">Lihat Foto</span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-full text-[10px] text-zinc-600 italic text-center">Belum ada laporan aset.</div>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    @empty
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-10 text-center shadow-xl">
                            <span class="text-xs text-zinc-500 font-bold uppercase tracking-widest">Klien ini belum memiliki pesanan aktif.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- MODAL EDIT / UPLOAD ULANG DOKUMEN -->
            <div x-show="modalEditDokumen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
                <div @click.away="modalEditDokumen = false" class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b border-zinc-800 pb-3">
                        <h3 class="text-base font-black text-white flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Upload Ulang / Ganti Berkas
                        </h3>
                        <button @click="modalEditDokumen = false" class="text-zinc-500 hover:text-white">&times;</button>
                    </div>
                    
                    <form :action="'/dokumen/update/' + activeDokumen.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-zinc-950 p-3 rounded-xl border border-zinc-800 mb-2">
                            <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-wider mb-1">Mengganti Dokumen:</p>
                            <p class="text-sm font-bold text-white" x-text="activeDokumen.jenis_dokumen"></p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-zinc-400 mb-1.5 uppercase tracking-widest">Pilih File Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="file_dokumen" required accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-zinc-950 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-4 py-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700 transition cursor-pointer shadow-inner">
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-800">
                            <button type="button" @click="modalEditDokumen = false" class="px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold rounded-xl transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-[0_0_15px_rgba(16,185,129,0.3)]">Simpan Pembaruan</button>
                        </div>
                    </form>
                </div>
            </div>

        <!-- ========================================================= -->
        <!-- 2. TAMPILAN LIST KLIEN UMUM (TAMPILAN ASLI MILIKMU)       -->
        <!-- ========================================================= -->
        @else
            <!-- HEADER, PENCARIAN & FILTER -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white mb-1">Data Finansial Klien 📂</h1>
                    <p class="text-sm text-zinc-400">Pantau rincian pengajuan dan cicilan per klien.</p>
                </div>
                
                <!-- FORM PENCARIAN & FILTER DROPDOWN -->
                <form action="{{ route('keuangan.klien') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                    
                    <div class="relative w-full sm:w-48">
                        <select name="filter" onchange="this.form.submit()" class="w-full bg-zinc-900 border border-zinc-800 text-white rounded-xl px-4 py-2.5 text-sm focus:border-emerald-500 outline-none transition appearance-none cursor-pointer">
                            <option value="semua" {{ (isset($filter) && $filter == 'semua') || empty($filter) ? 'selected' : '' }}>Semua Pilihan</option>
                            <option value="termin" {{ isset($filter) && $filter == 'termin' ? 'selected' : '' }}>Tahap Termin (Belum Lunas)</option>
                            <option value="lunas" {{ isset($filter) && $filter == 'lunas' ? 'selected' : '' }}>Riwayat Sudah Lunas</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-zinc-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama klien..." class="w-full bg-zinc-900 border border-zinc-800 text-white rounded-xl pl-10 pr-10 py-2.5 text-sm focus:border-emerald-500 outline-none transition">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        
                        @if(request('search'))
                            <a href="{{ route('keuangan.klien', ['filter' => $filter ?? 'semua']) }}" class="absolute right-3 top-3 text-zinc-500 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="hidden"></button>
                </form>
            </div>

            <!-- DAFTAR FOLDER KLIEN -->
            <div class="space-y-4">
                @forelse($klien_list as $klien)
                    @php
                        // LOGIKA BARU PENENTUAN STATUS TERMIN
                        $adaValidasi = false;
                        $terminValidasiKe = 0;
                        
                        $terminLunasTerakhir = 0;
                        $terminBelumLunasTerawal = 0;
                        $semuaPesananLunas = true;

                        foreach($klien->pengajuan as $p) {
                            if (!in_array($p->status_pengajuan, ['Lunas / Aktif', 'Selesai'])) {
                                $semuaPesananLunas = false;
                            }
                            foreach($p->termins as $t) {
                                if ($t->status_termin == 'Menunggu Verifikasi Admin' || (!empty($t->bukti_pembayaran) && $t->status_termin != 'Lunas')) {
                                    $adaValidasi = true;
                                    $terminValidasiKe = $t->termin_ke;
                                } elseif ($t->status_termin == 'Lunas') {
                                    if ($t->termin_ke > $terminLunasTerakhir) {
                                        $terminLunasTerakhir = $t->termin_ke;
                                    }
                                } else {
                                    if ($terminBelumLunasTerawal == 0 || $t->termin_ke < $terminBelumLunasTerawal) {
                                        $terminBelumLunasTerawal = $t->termin_ke;
                                    }
                                }
                            }
                        }

                        // Tentukan Label dan Warna
                        if ($adaValidasi) {
                            $colorKlien = 'bg-amber-500/20 text-amber-400 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.2)]';
                            $labelKlien = 'Validasi Termin ' . $terminValidasiKe;
                        } elseif ($semuaPesananLunas && $klien->pengajuan->count() > 0) {
                            $colorKlien = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                            $labelKlien = 'Semua Lunas';
                        } elseif ($terminLunasTerakhir > 0) {
                            $colorKlien = 'bg-blue-500/20 text-blue-400 border border-blue-500/30';
                            $labelKlien = 'Lunas Termin ' . $terminLunasTerakhir;
                        } elseif ($terminBelumLunasTerawal > 0) {
                            $colorKlien = 'bg-red-500/20 text-red-400 border border-red-500/30';
                            $labelKlien = 'Tahap Termin ' . $terminBelumLunasTerawal;
                        } else {
                            $colorKlien = 'bg-zinc-500/20 text-zinc-400 border border-zinc-500/30';
                            $labelKlien = 'Menunggu Skema';
                        }
                    @endphp

                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-xl">
                        
                        <div onclick="toggleKlien({{ $klien->id }})" class="p-6 flex justify-between items-center cursor-pointer hover:bg-zinc-800/50 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-zinc-800 rounded-full flex items-center justify-center text-emerald-400 font-black text-lg uppercase shadow-inner">
                                    {{ substr($klien->nama_perusahaan ?: $klien->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white">{{ $klien->nama_perusahaan ?: $klien->name }}</h3>
                                    <p class="text-xs text-zinc-400 mt-0.5">
                                        PIC: <span class="text-zinc-300">{{ $klien->name }}</span> • 
                                        <span class="font-mono text-zinc-500">{{ $klien->no_wa ?? 'No HP Tidak Ada' }}</span> • 
                                        {{ $klien->email }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-[10px] font-bold px-3 py-1.5 rounded-full {{ $colorKlien }} uppercase tracking-wider hidden sm:block">
                                    {{ $labelKlien }}
                                </span>
                                
                                <span class="text-xs font-bold bg-zinc-800 text-zinc-300 px-3 py-1.5 rounded-full shadow-sm">{{ count($klien->pengajuan) }} Pesanan</span>
                                <div class="w-8 h-8 flex items-center justify-center bg-zinc-800 rounded-full">
                                    <svg id="arrow-klien-{{ $klien->id }}" class="w-4 h-4 text-zinc-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div id="content-klien-{{ $klien->id }}" class="hidden border-t border-zinc-800 bg-zinc-950/80 p-6 space-y-6">
                            
                            <!-- TOMBOL SAKTI KE FOLDER DOKUMEN -->
                            <div class="flex justify-start mb-2">
                                <a href="{{ route('keuangan.klien.detail', $klien->id) }}" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    Buka Folder Dokumen & Tagihan Klien Ini
                                </a>
                            </div>

                            @forelse($klien->pengajuan as $pesanan)
                                <div class="bg-zinc-900 border border-zinc-800 p-5 rounded-2xl">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-5 border-b border-zinc-800 pb-4 gap-3">
                                        <div>
                                            <h4 class="text-sm font-bold text-white mb-1">Pesanan #{{ $pesanan->nomor_pengajuan ?? $pesanan->id }}</h4>
                                            <p class="text-xs text-zinc-400">Total Kesepakatan: <span class="text-emerald-400 font-black">Rp {{ number_format($pesanan->harga_final ?? $pesanan->estimasi_harga, 0, ',', '.') }}</span></p>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold px-3 py-1.5 rounded-full {{ $pesanan->status_pengajuan == 'Lunas / Aktif' || $pesanan->status_pengajuan == 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                                Status: {{ $pesanan->status_pengajuan }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        @foreach($pesanan->termins as $termin)
                                            <div class="flex flex-col sm:flex-row justify-between sm:items-center bg-zinc-950 p-4 rounded-xl border border-zinc-800 gap-4">
                                                
                                                <div>
                                                    <p class="text-xs font-black text-white mb-1 flex items-center">
                                                        Termin #{{ $termin->termin_ke }} 
                                                        <span class="text-zinc-500 font-normal ml-2 bg-zinc-900 px-2 py-0.5 rounded text-[10px]">Porsi: {{ $termin->persentase }}%</span>
                                                    </p>
                                                    <p class="text-[10px] text-zinc-400 mb-2">Batas Bayar: <span class="text-zinc-200">{{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d M Y') }}</span></p>
                                                    
                                                    @php
                                                        $adaInvoice = false;
                                                        $linkInvoice = '';
                                                        if ($termin->keterangan && strpos($termin->keterangan, 'INVOICE:') !== false) {
                                                            $adaInvoice = true;
                                                            $linkInvoice = str_replace('INVOICE:', '', $termin->keterangan);
                                                        }
                                                    @endphp

                                                    <div class="flex flex-wrap gap-2 mt-2">
                                                        @if($termin->bukti_pembayaran)
                                                            <a href="{{ asset('storage/' . $termin->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1.5 rounded-lg hover:bg-emerald-500 hover:text-white transition shadow-sm">
                                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                                Lihat Bukti
                                                            </a>
                                                        @endif

                                                        @if($adaInvoice)
                                                            <a href="{{ asset('storage/' . $linkInvoice) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-blue-400 bg-blue-500/10 border border-blue-500/30 px-3 py-1.5 rounded-lg hover:bg-blue-500 hover:text-white transition shadow-sm">
                                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                Lihat Invoice
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="sm:text-right flex flex-col justify-between h-full">
                                                    <p class="text-sm font-black text-white mb-2 sm:mb-1">Rp {{ number_format($termin->nominal, 0, ',', '.') }}</p>
                                                    
                                                    <div>
                                                        @if($termin->status_termin == 'Lunas')
                                                            <div class="flex flex-col sm:items-end space-y-2 mt-2 sm:mt-0">
                                                                <span class="text-[10px] bg-emerald-500 text-white px-3 py-1 rounded-md uppercase font-bold tracking-wider flex sm:justify-end items-center sm:inline-flex shadow-[0_0_10px_rgba(16,185,129,0.3)]">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lunas
                                                                </span>
                                                            </div>
                                                        @elseif($termin->status_termin == 'Menunggu Verifikasi Admin' || $termin->bukti_pembayaran != null)
                                                            <span class="text-[10px] bg-amber-500/20 text-amber-400 border border-amber-500/30 px-3 py-1 rounded-md uppercase font-bold tracking-wider">Perlu Validasi</span>
                                                        @else
                                                            <span class="text-[10px] bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1 rounded-md uppercase font-bold tracking-wider">Belum Bayar</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-zinc-500 italic">Tidak ada data pesanan pada klien ini.</p>
                            @endforelse
                        </div>

                    </div>
                @empty
                    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-12 flex flex-col items-center justify-center text-center shadow-xl">
                        <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-zinc-400 font-medium mb-1">Data tidak ditemukan.</p>
                        <p class="text-xs text-zinc-500 mb-4">Klien dengan kategori filter atau nama tersebut belum tersedia.</p>
                        @if(request('search') || request('filter') != 'semua')
                            <a href="{{ route('keuangan.klien') }}" class="bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition">Tampilkan Semua Klien</a>
                        @endif
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Script Accordion untuk List Keuangan (Sudah bawaan aslimu) -->
    @if(!isset($klienDetail))
    <script>
        function toggleKlien(id) {
            const content = document.getElementById('content-klien-' + id);
            const arrow = document.getElementById('arrow-klien-' + id);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }
    </script>
    @endif
</x-boma-layout>