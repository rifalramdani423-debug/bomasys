<x-boma-layout>
    <!-- Alpine JS disatukan untuk Klien List & Dokumen Detail -->
    <div class="flex flex-col space-y-6 pb-10" x-data="{ 
        modalTambah: false, 
        modalEdit: false, 
        activeKlien: {},
        modalEditDokumen: false, 
        activeDokumen: {}, 
        jenisDokumen: '',
        kategoriPesanan: ['Bukti Pembayaran', 'Invoice', 'Kwitansi', 'SKPD', 'BAST', 'Purchase Order', 'Lainnya']
    }">
        
        <!-- ========================================================= -->
        <!-- 1. TAMPILAN DETAIL ARSIP KLIEN (MUNCUL JIKA ADA ID KLIEN) -->
        <!-- ========================================================= -->
        @if(isset($klienDetail))
            
            @php
                // Logika Penarikan Data Dinamis Khusus Klien Ini
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
                    <a href="{{ route('admin.klien') }}" class="inline-flex items-center text-xs font-bold text-zinc-400 hover:text-white transition mb-4 bg-zinc-900 border border-zinc-800 px-3 py-1.5 rounded-lg">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Daftar Klien
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Arsip: {{ $klienDetail->nama_perusahaan ?? $klienDetail->name }} 📂</h1>
                    <p class="text-sm text-zinc-400 mt-1">Manajemen kelengkapan dokumen dan folder pesanan klien.</p>
                </div>
                <div class="flex gap-3">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $klienDetail->no_wa ?? '') }}" target="_blank" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-[0_0_15px_rgba(16,185,129,0.4)] shrink-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Hubungi Klien
                    </a>
                    <a href="{{ route('admin.klien.unduh-arsip', $klienDetail->id) }}" class="inline-flex items-center justify-center bg-zinc-800 hover:bg-zinc-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition border border-zinc-700 shrink-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Unduh ZIP
                    </a>
                </div>
            </div>

            <!-- KARTU INFORMASI PROFIL KLIEN (PERBAIKAN ALIGNMENT VERTICAL) -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl flex flex-col md:flex-row items-center md:justify-start justify-center gap-6 relative overflow-hidden mt-2">
                <div class="absolute right-0 top-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl pointer-events-none -mr-10 -mt-10"></div>
                
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
                
                <!-- KOLOM KIRI: FORM UPLOAD KHUSUS INTERNAL -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-zinc-800 bg-zinc-950/50 shrink-0">
                        <h3 class="text-md font-bold text-white flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Unggah Berkas ke Klien
                        </h3>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Pastikan Route ini dibuat di web.php untuk Admin menyimpan dokumen Klien -->
                        <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                            @csrf
                            <!-- Menempelkan Dokumen ke ID Klien ini -->
                            <input type="hidden" name="user_id" value="{{ $klienDetail->id }}">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 mb-2 uppercase tracking-widest">Kategori Berkas *</label>
                                    <div class="relative">
                                        <select name="jenis_dokumen" x-model="jenisDokumen" required class="appearance-none w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition cursor-pointer shadow-inner">
                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                            @if($role === 'keuangan' || $role === 'finance')
                                                <option value="Invoice">Invoice / Tagihan Resmi</option>
                                                <option value="Kwitansi">Kwitansi Pembayaran Lunas</option>
                                                <option value="Lainnya">Lainnya (Ketik Manual)</option>
                                            @else
                                                <option value="SKPD">SKPD (Pajak Reklame)</option>
                                                <option value="BAST">BAST (Berita Acara Serah Terima)</option>
                                                <option value="Surat Izin Perusahaan">Surat Izin Reklame / Pemda</option>
                                                <option value="Purchase Order">Purchase Order (PO)</option>
                                                <option value="Invoice">Invoice / Tagihan Resmi</option>
                                                <option value="Lainnya">Lainnya (Ketik Manual)</option>
                                            @endif
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-zinc-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- MUNCUL JIKA MEMILIH "Lainnya" -->
                                <div x-show="jenisDokumen === 'Lainnya'" style="display: none;" x-transition>
                                    <label class="block text-[10px] font-bold text-zinc-400 mb-2 uppercase tracking-widest">Nama Dokumen Kustom *</label>
                                    <input type="text" name="jenis_dokumen_custom" placeholder="Contoh: MoU Kesepakatan" :required="jenisDokumen === 'Lainnya'" class="w-full bg-zinc-950 border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner">
                                </div>

                                <!-- MUNCUL OTOMATIS JIKA KATEGORI TRANSAKSIONAL DIPILIH -->
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
                                    <input type="file" name="file_dokumen" required accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-zinc-950 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700 transition cursor-pointer shadow-inner">
                                </div>
                            </div>

                            <div class="mt-auto pt-6">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition text-sm md:text-base shadow-[0_0_20px_rgba(37,99,235,0.35)] flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Unggah & Simpan ke Arsip Klien
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- KOLOM KANAN: ARSIP PROFIL IDENTITAS -->
                <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-zinc-800 bg-zinc-950/50 shrink-0 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
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
                                            
                                            <!-- Admin bebas edit KTP/NPWP jika salah -->
                                            @if($role === 'superadmin' || $role === 'admin')
                                                <button @click="activeDokumen = {{ json_encode($dok) }}; modalEditDokumen = true" class="inline-block text-[10px] font-bold text-zinc-300 bg-zinc-800 hover:bg-zinc-700 hover:text-white px-3 py-1.5 rounded-lg border border-zinc-700 transition">Edit</button>
                                            @endif
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
                            // Mengelompokkan berdasarkan divisi yang mengunggah
                            $dokKeuangan = $docsPesanan->whereIn('jenis_dokumen', ['Invoice', 'Kwitansi', 'Bukti Pembayaran']);
                            $dokAdmin    = $docsPesanan->whereNotIn('jenis_dokumen', ['Invoice', 'Kwitansi', 'Bukti Pembayaran']); // SKPD, BAST, PO, Lainnya
                            $visual      = $materiVisualList->where('id', $pesanan->id)->first();
                            
                            $titikPesanan = $pesanan->details->pluck('kode_titik')->toArray();
                        $laporanPesananIni = collect($laporanTayang ?? [])->filter(function($t) use ($titikPesanan) {
                            $tArray = json_decode($t->kode_titik, true);
                            if(is_array($tArray)) {
                                $cleanTArray = array_map(function($item) {
                                    return trim(explode(' - ', $item)[0]);
                                }, $tArray);
                                return count(array_intersect($cleanTArray, $titikPesanan)) > 0;
                            }
                            
                            $cleanT = trim(explode(' - ', $t->kode_titik)[0]);
                            return in_array($cleanT, $titikPesanan);
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
                                                    
                                                    <!-- Hanya Keuangan & Superadmin yg bisa edit kolom ini -->
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
                                                    
                                                    <!-- Hanya Admin & Superadmin yg bisa edit kolom ini -->
                                                    @if($role === 'superadmin' || $role === 'admin')
                                                        <button @click="activeDokumen = {{ json_encode($dok) }}; modalEditDokumen = true" class="text-[8px] font-bold text-center text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded hover:bg-zinc-700 hover:text-white transition w-full">Edit</button>
                                                    @endif
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
                        <!-- PERBAIKAN: Looping semua foto, bukan cuma index [0] -->
                        @foreach($fotoKlien as $pathFoto)
                            <a href="{{ asset('storage/' . $pathFoto) }}" target="_blank" class="block w-full h-16 rounded-lg border border-zinc-700 overflow-hidden relative group">
                                <img src="{{ asset('storage/' . $pathFoto) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                    <span class="text-[8px] font-bold text-white">Lihat Foto</span>
                                </div>
                            </a>
                        @endforeach
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
                            <input type="file" name="file_dokumen" required accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-zinc-950 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700 transition cursor-pointer shadow-inner">
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-800">
                            <button type="button" @click="modalEditDokumen = false" class="px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold rounded-xl transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-[0_0_15px_rgba(37,99,235,0.3)]">Simpan Pembaruan</button>
                        </div>
                    </form>
                </div>
            </div>

        <!-- ========================================================= -->
        <!-- 2. TAMPILAN LIST KLIEN UMUM (MUNCUL JIKA TIDAK ADA ID) -->
        <!-- ========================================================= -->
        @else

            <!-- HEADER & TOMBOL TAMBAH KLIEN -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white mb-1.5 tracking-tight">Arsip Data Klien 🗂️</h1>
                    <p class="text-xs sm:text-sm text-zinc-400">Daftar lengkap klien, status pemesanan, dan arsip dokumen terpusat.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                    <!-- Tombol Tambah -->
                    <button @click="modalTambah = true" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.4)]">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Klien Baru
                    </button>

                    <!-- Pencarian & Filter Status -->
<form method="GET" action="{{ route('admin.klien') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
    
    <!-- Dropdown Filter -->
    <div class="w-full sm:w-56">
        <select name="status" onchange="this.form.submit()" class="w-full bg-zinc-900 border border-zinc-700 text-zinc-300 text-xs rounded-xl px-4 py-2.5 focus:border-red-500 outline-none transition cursor-pointer shadow-inner">
            <option value="">Semua Status Klien</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>🟢 Masa Sewa Aktif (Tayang)</option>
            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>🟡 Sedang Dalam Proses</option>
            <option value="inaktif" {{ request('status') == 'inaktif' ? 'selected' : '' }}>⚫ Tidak Aktif / Kosong</option>
        </select>
    </div>

    <!-- Search Bar Asli -->
    <div class="relative w-full sm:w-64">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perusahaan / klien..." class="w-full bg-zinc-900 border border-zinc-700 text-zinc-300 text-xs rounded-xl pl-9 pr-3 py-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition">
        <button type="submit" class="absolute left-3 top-3">
            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
    </div>
</form>
                </div>
            </div>

            <!-- Notifikasi Sukses / Error -->
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-4 py-3 rounded-xl text-sm font-bold flex items-center shadow-lg mt-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- TABEL DAFTAR KLIEN -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl shadow-xl overflow-hidden mt-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-400">
                        <thead class="text-[10px] text-zinc-500 uppercase bg-zinc-950/80 tracking-widest border-b border-zinc-800">
                            <tr>
                                <th class="px-6 py-4 font-bold">Profil Perusahaan / Klien</th>
                                <th class="px-6 py-4 font-bold">Kontak Utama</th>
                                <th class="px-6 py-4 font-bold">Waktu Mendaftar</th>
                                <th class="px-6 py-4 font-bold">Total Pesanan</th>
                                <th class="px-6 py-4 font-bold text-right">Aksi Manajerial</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            @forelse ($klien_list as $klien)
                                <tr class="hover:bg-zinc-800/40 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-white text-base mb-0.5">{{ $klien->nama_perusahaan ?? $klien->name }}</div>
                                        <div class="text-[10px] bg-zinc-950 border border-zinc-700 inline-block px-2.5 py-0.5 rounded text-zinc-400 shadow-inner">PIC: {{ $klien->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-white font-medium">{{ $klien->email }}</div>
                                        <div class="text-[10px] text-emerald-400 font-bold font-mono">{{ $klien->no_wa ?? 'WA Tidak Ada' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <!-- Menampilkan Tanggal dan Jam Pendaftaran -->
                                        <div class="text-white font-bold text-xs">{{ $klien->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-zinc-500 font-mono mt-0.5 flex items-center">
                                            <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $klien->created_at->format('H:i:s') }} WIB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="bg-zinc-950 inline-flex items-center px-3 py-1.5 rounded-lg border border-zinc-800">
                                            <span class="text-lg font-black text-white mr-1.5">{{ $klien->pengajuan->count() }}</span>
                                            <span class="text-[10px] text-zinc-500 uppercase font-bold tracking-wider">Pengajuan</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <!-- Tombol Folder Spesifik -->
                                        <a href="{{ route('admin.klien.detail', $klien->id) }}" class="inline-flex items-center bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-white px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                                            Folder
                                        </a>
                                        <!-- Tombol Edit -->
                                        <button @click="activeKlien = {{ json_encode($klien) }}; modalEdit = true" class="inline-flex items-center bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-zinc-300 px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                                            Edit
                                        </button>
                                        <!-- Tombol Hapus Anti-Bot -->
                                        <form action="{{ route('admin.klien.delete', $klien->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus akun klien ini secara permanen? Seluruh data pesanan mereka juga bisa ikut terhapus lho.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center bg-red-600/10 border border-red-500/30 hover:bg-red-600 text-red-500 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                                        <svg class="w-12 h-12 text-zinc-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Belum ada data klien yang terdaftar di sistem.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL TAMBAH KLIEN -->
            <div x-show="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
                <div @click.away="modalTambah = false" class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b border-zinc-800 pb-3">
                        <h3 class="text-base font-black text-white">Tambah Klien Baru</h3>
                        <button @click="modalTambah = false" class="text-zinc-500 hover:text-white">&times;</button>
                    </div>
                    <form action="{{ route('admin.klien.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Nama Instansi/Klien *</label>
                            <input type="text" name="name" required class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Email *</label>
                            <input type="email" name="email" required class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Nomor WhatsApp</label>
                            <input type="text" name="no_wa" placeholder="08xxxxxxxxxx" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Password Sementara *</label>
                            <input type="password" name="password" required minlength="6" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-800">
                            <button type="button" @click="modalTambah = false" class="px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold rounded-xl transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-lg">Simpan Klien</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL EDIT KLIEN -->
            <div x-show="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
                <div @click.away="modalEdit = false" class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b border-zinc-800 pb-3">
                        <h3 class="text-base font-black text-white">Edit Data Klien</h3>
                        <button @click="modalEdit = false" class="text-zinc-500 hover:text-white">&times;</button>
                    </div>
                    <form :action="'/admin/klien/update/' + activeKlien.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Nama Instansi/Klien *</label>
                            <input type="text" name="name" x-model="activeKlien.name" required class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Email *</label>
                            <input type="email" name="email" x-model="activeKlien.email" required class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Nomor WhatsApp</label>
                            <input type="text" name="no_wa" x-model="activeKlien.no_wa" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Password Baru <span class="text-[9px] text-zinc-600 normal-case">(Kosongkan jika tidak diubah)</span></label>
                            <input type="password" name="password" minlength="6" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 text-sm focus:border-red-500 outline-none">
                        </div>
                        <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-800">
                            <button type="button" @click="modalEdit = false" class="px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold rounded-xl transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-lg">Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>

        @endif

    </div>
</x-boma-layout>