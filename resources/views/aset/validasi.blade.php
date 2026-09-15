<x-boma-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Validasi Laporan QC (Real-Time)</h1>
            <p class="text-sm text-zinc-400 mt-1">Centang foto jika ingin dikirimkan ke klien, lalu klik Selesai.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-4 rounded-xl flex items-center space-x-3 shadow-lg">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-5 py-4 rounded-xl flex items-center space-x-3 shadow-lg">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-5 py-4 rounded-xl shadow-lg">
            <ul class="list-disc pl-4 text-sm font-bold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Daftar Laporan -->
    <div class="space-y-8">
        @forelse($laporans as $laporan)
            @php
                $titikArray = json_decode($laporan->kode_titik, true);
                $titikRapi = is_array($titikArray) ? implode(' | ', $titikArray) : $laporan->kode_titik;
                $fotoHasil = json_decode($laporan->foto_hasil, true) ?? [];
                
                $isInternalOnly = str_contains(strtolower($laporan->jenis_pekerjaan), 'h-3') || str_contains(strtolower($laporan->jenis_pekerjaan), 'internal');

                // ===============================================
                // LOGIKA BARU: KOMPARASI ABSENSI ANGGOTA TIM
                // ===============================================
                $anggotaHadirText = $laporan->anggota_tim ?? '';
                $anggotaHadirArray = array_map('trim', explode(',', $anggotaHadirText));
                
                // Ambil daftar anggota yang seharusnya bertugas dari tabel internal_memos
                $im = \App\Models\InternalMemo::where('no_im', $laporan->no_im)->first();
                $semuaAnggotaDitugaskan = [];
                
                if ($im && $im->catatan && strpos($im->catatan, '|') !== false) {
                    $pecahan = explode('|', $im->catatan, 2); 
                    $bagianAnggota = trim($pecahan[0]); 
                    $daftarAnggotaIM = str_replace('Daftar_Anggota: ', '', $bagianAnggota);
                    $semuaAnggotaDitugaskan = array_map('trim', explode(',', $daftarAnggotaIM));
                }

                // Gabungkan kedua array (yang ditugaskan + yang hadir, untuk jaga-jaga ada anggota cabutan)
                $semuaAnggotaUnik = array_unique(array_merge($semuaAnggotaDitugaskan, $anggotaHadirArray));
                $semuaAnggotaUnik = array_filter($semuaAnggotaUnik); // Buang yang kosong
            @endphp

            <form action="{{ route('aset.validasi.proses', $laporan->id) }}" method="POST" x-data="{ fotoTerpilih: [], showRevisi: false }" class="bg-zinc-900 rounded-2xl border border-zinc-800 shadow-xl relative overflow-hidden">
                @csrf
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isInternalOnly ? 'bg-zinc-500' : 'bg-blue-500' }}"></div>

                <div class="p-6 flex flex-col lg:flex-row gap-8">
                    <!-- KOLOM KIRI (Info & Foto) -->
                    <div class="flex-1 w-full pl-2">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <span class="bg-blue-500/20 text-blue-400 text-xs font-black px-2.5 py-1 rounded shadow-sm">{{ $laporan->no_im }}</span>
                            <span class="bg-zinc-950 border border-zinc-800 text-zinc-400 text-[10px] font-bold px-2 py-1 rounded">{{ \Carbon\Carbon::parse($laporan->tanggal_dikerjakan)->format('d M Y - H:i') }}</span>
                            <span class="bg-orange-500/20 text-orange-400 text-[10px] font-bold px-2 py-1 rounded">Regu: {{ $laporan->tim_pelaksana }}</span>
                            
                            @if($isInternalOnly)
                                <span class="bg-zinc-800 text-zinc-400 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded">Tugas Internal</span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-black text-white leading-tight mb-1">{{ $laporan->jenis_pekerjaan }}</h3>
                        <p class="text-sm text-zinc-400 mb-2">Visual / Klien: <span class="text-white font-bold">{{ $laporan->klien_visual ?? 'Internal' }}</span></p>
                        
                        <!-- KOTAK LOKASI DAN ABSENSI -->
                        <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800 mb-4 space-y-3">
                            <div class="flex items-start">
                                <span class="font-bold text-zinc-500 w-20 flex-shrink-0 text-xs mt-0.5">Lokasi:</span> 
                                <span class="text-emerald-400 font-bold text-sm leading-snug">{{ $titikRapi }}</span>
                            </div>
                            
                            <div class="flex items-start border-t border-zinc-900 pt-3">
                                <span class="font-bold text-zinc-500 w-20 flex-shrink-0 text-xs mt-1">Kehadiran:</span> 
                                <div class="flex-1 flex flex-wrap gap-2">
                                    @if(count($semuaAnggotaUnik) > 0)
                                        @foreach($semuaAnggotaUnik as $namaAnggota)
                                            @if(in_array($namaAnggota, $anggotaHadirArray))
                                                <!-- Jika Hadir: Hijau -->
                                                <span class="inline-flex items-center text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-1 rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    {{ $namaAnggota }}
                                                </span>
                                            @else
                                                <!-- Jika Tidak Hadir: Merah dan Dicoret -->
                                                <span class="inline-flex items-center text-[10px] font-medium bg-red-950/30 text-red-500 border border-red-900/50 px-2 py-1 rounded line-through decoration-red-500/50" title="Ditugaskan tapi tidak hadir">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    {{ $namaAnggota }} <span class="ml-1 text-[8px] opacity-75 italic">(Alpa)</span>
                                                </span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-xs text-zinc-600 italic">Data absensi anggota tidak tersedia.</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-400 mb-4 bg-zinc-800/30 p-3 rounded-lg border border-zinc-800">
                            <strong class="text-zinc-500 block mb-1">Catatan Lapangan (Produksi):</strong> 
                            {{ $laporan->catatan_lapangan ?: 'Tidak ada kendala / catatan lapangan.' }}
                        </p>
                        
                        <!-- AREA FOTO -->
                        <div class="border-t border-zinc-800 pt-5 mt-5">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-xs font-black text-white uppercase tracking-wider">Bukti Dokumentasi (QC)</p>
                                    <p class="text-[10px] text-zinc-500 mt-0.5">Pilih foto yang layak (opsional) jika ingin ditambahkan ke arsip klien.</p>
                                </div>
                                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-bold px-3 py-1.5 rounded-lg" x-show="fotoTerpilih.length > 0">
                                    <span x-text="fotoTerpilih.length"></span> Foto Dipilih
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                @if(count($fotoHasil) > 0)
                                    @foreach($fotoHasil as $index => $foto)
                                        <label class="relative group cursor-pointer w-36 select-none">
                                            <input type="checkbox" name="foto_klien[]" value="{{ $foto['path'] }}" x-model="fotoTerpilih" class="hidden">
                                            <p class="text-[10px] text-zinc-400 mb-1.5 font-bold truncate" title="{{ $foto['kategori'] }}">{{ $foto['kategori'] }}</p>
                                            <div class="block overflow-hidden rounded-xl border-2 transition-all duration-200" 
                                                 :class="fotoTerpilih.includes('{{ $foto['path'] }}') ? 'border-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)] scale-105' : 'border-zinc-700 hover:border-zinc-500'">
                                                <img src="{{ asset('storage/' . $foto['path']) }}" class="w-full h-36 object-cover">
                                                <div x-show="fotoTerpilih.includes('{{ $foto['path'] }}')" class="absolute inset-0 bg-emerald-500/20 flex items-center justify-center mt-5">
                                                    <div class="bg-emerald-500 text-white rounded-full p-1.5 shadow-xl transform scale-110">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- KOLOM CATATAN REVISI (Tampil jika tombol Minta Revisi ditekan) -->
                        <div x-show="showRevisi" x-collapse class="mt-6 border-t border-red-900/30 pt-4">
                            <label class="block text-xs font-bold text-red-400 mb-2">Alasan Permintaan Revisi *</label>
                            <textarea name="catatan_revisi" rows="2" placeholder="Contoh: Foto siang blur, tolong foto ulang dengan jelas..." class="w-full bg-red-950/20 border border-red-900/50 rounded-lg p-3 text-white text-xs focus:border-red-500 outline-none"></textarea>
                            <p class="text-[10px] text-zinc-500 mt-1">Catatan ini akan dikirimkan kembali ke aplikasi HP Tim Produksi.</p>
                        </div>
                    </div>
                    
                    <!-- KOLOM KANAN (Tombol Aksi) -->
                    <div class="flex flex-col gap-3 w-full lg:w-56 shrink-0 border-t lg:border-t-0 lg:border-l border-zinc-800 pt-5 lg:pt-0 lg:pl-6 justify-center">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider text-center mb-1">Pilih Tindakan</p>
                        
                        <!-- Tombol Selesai (Dynamic Text) -->
                        <button type="submit" name="aksi" value="terima" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white p-3.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-600/20 transition flex flex-col items-center text-center leading-tight">
                            <span>✅ Selesai & ACC</span>
                            <span class="text-[9px] font-medium text-emerald-200 mt-1 normal-case font-normal" x-text="fotoTerpilih.length > 0 ? '(Otomatis kirim ' + fotoTerpilih.length + ' foto ke klien)' : '(Hanya simpan di Arsip BOMA)'"></span>
                        </button>
                        
                        <div class="h-px bg-zinc-800 my-1"></div>

                        <!-- Tombol Toggle Revisi -->
                        <button type="button" @click="showRevisi = !showRevisi" x-show="!showRevisi" class="w-full bg-zinc-950 hover:bg-zinc-800 border border-zinc-700 text-zinc-400 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Tulis Revisi
                        </button>

                        <!-- Tombol Submit Revisi yang sebenarnya (Tampil jika form revisi terbuka) -->
                        <button type="submit" name="aksi" value="revisi" x-show="showRevisi" class="w-full bg-red-950/50 hover:bg-red-600 border border-red-900/50 text-red-400 hover:text-white py-3 rounded-xl text-xs font-bold transition flex items-center justify-center shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                            Kirim Tolakan & Revisi
                        </button>
                    </div>
                </div>
            </form>
        @empty
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-10 text-center flex flex-col items-center justify-center shadow-xl">
                <svg class="w-16 h-16 text-zinc-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="text-white font-bold text-lg mb-1">Semua Bersih!</h3>
                <p class="text-sm text-zinc-500">Tidak ada laporan QC yang menunggu validasi saat ini.</p>
            </div>
        @endforelse
    </div>
</x-boma-layout>