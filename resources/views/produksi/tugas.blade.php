<x-boma-layout>
    <div class="max-w-2xl mx-auto pb-20 md:pb-10">
        
        <div class="flex items-center mb-6">
            <a href="{{ route('produksi.index') }}" class="mr-3 p-2 bg-zinc-800 rounded-full text-zinc-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-white">Laporan Eksekusi (QC)</h1>
            </div>
        </div>

        @php
            $kebutuhanFoto = json_decode($tugasAktif->kebutuhan_foto, true) ?? ['Foto Dokumentasi Standar'];
            
            $titikList = json_decode($tugasAktif->kode_titik, true);
            if (!is_array($titikList)) {
                $titikList = [$tugasAktif->kode_titik]; 
            }

            // Ekstraksi Catatan dan Daftar Anggota
            $catatanPenuh = $tugasAktif->catatan ?? '';
            $daftarAnggotaArray = [];
            $catatanAsli = $catatanPenuh;

            // Jika mengandung format khusus kita
            if (strpos($catatanPenuh, 'Daftar_Anggota:') !== false) {
                $pecah = explode(' | ', $catatanPenuh);
                if(count($pecah) >= 2) {
                    $stringAnggota = str_replace('Daftar_Anggota: ', '', $pecah[0]);
                    $daftarAnggotaArray = explode(', ', $stringAnggota);
                    $catatanAsli = $pecah[1]; // Ambil catatannya saja
                }
            }
        @endphp

        <!-- KARTU INSTRUKSI ASET -->
        <div class="bg-orange-950/30 border border-orange-900/50 rounded-2xl p-5 mb-6 shadow-lg relative">
            <div class="flex justify-between items-start mb-3">
                <span class="bg-orange-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-wider shadow-sm">INSTRUKSI ASET</span>
                <span class="text-xs text-zinc-400 font-mono">{{ $tugasAktif->no_im }}</span>
            </div>
            
            <h2 class="text-lg font-bold text-white mb-1">{{ $tugasAktif->perihal }}</h2>
            <p class="text-xs text-zinc-400 mb-4">Visual: <span class="text-white font-bold">{{ $tugasAktif->klien_visual }}</span></p>
            
            <div class="bg-zinc-950 p-3 rounded-xl border border-zinc-800 mb-4">
                <p class="text-[10px] text-zinc-500 font-bold mb-1 uppercase tracking-wider">Titik Reklame yang Dikerjakan:</p>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($titikList as $titik)
                        <span class="bg-zinc-800 text-orange-400 px-2.5 py-1 rounded text-xs font-bold font-mono border border-zinc-700">
                            {{ $titik }}
                        </span>
                    @endforeach
                </div>
            </div>

            @if($tugasAktif->gambar_acuan)
                <div class="mb-4 bg-blue-900/20 border border-blue-800/50 p-3 rounded-xl">
                    <p class="text-xs font-bold text-blue-400 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Referensi Visual / Desain dari Aset:
                    </p>
                    <a href="{{ asset('storage/' . $tugasAktif->gambar_acuan) }}" target="_blank" class="block overflow-hidden rounded-lg border border-blue-500/30 group">
                        <img src="{{ asset('storage/' . $tugasAktif->gambar_acuan) }}" class="w-full max-h-48 object-cover group-hover:scale-105 transition duration-300">
                    </a>
                </div>
            @endif

            <div class="p-3 bg-zinc-900/80 rounded-xl text-xs text-zinc-300 border border-zinc-800">
                <strong class="text-zinc-500 mb-1 block">Catatan Tambahan Aset:</strong>
                {{ $catatanAsli ?: 'Tidak ada catatan tambahan.' }}
            </div>
        </div>

        <!-- FORM EKSEKUSI PRODUKSI -->
        <form action="{{ route('produksi.laporan.store') }}" method="POST" enctype="multipart/form-data" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl" x-data="{ previews: {}, anggotaHadir: ['{{ auth()->user()->name }}'] }">
            @csrf
            
            <input type="hidden" name="no_im" value="{{ $tugasAktif->no_im }}">
            <input type="hidden" name="kode_titik" value="{{ $tugasAktif->kode_titik }}">
            <input type="hidden" name="jenis_pekerjaan" value="{{ $tugasAktif->perihal }}">
            
            <!-- Hidden input untuk menangkap array dari Alpine dan mengirimnya sebagai string comma-separated -->
            <input type="hidden" name="anggota_tim" :value="anggotaHadir.join(', ')">

            <div class="space-y-6">
                <!-- 1. INPUT ANGGOTA TIM (ABSEN KULI/TEKNISI) BERDASARKAN PILIHAN ASET -->
                <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800">
                    <label class="block text-sm font-bold text-zinc-300 mb-1">Absensi Anggota Lapangan *</label>
                    <p class="text-[10px] text-zinc-500 mb-3">Centang anggota tim yang hadir dan ikut bekerja hari ini sesuai daftar dari Aset.</p>
                    
                    <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                        <!-- Ketua Tim selalu hadir dan dicentang -->
                        <label class="flex items-center space-x-3 opacity-70">
                            <input type="checkbox" checked disabled class="rounded border-zinc-700 text-orange-600 bg-zinc-900 cursor-not-allowed">
                            <span class="text-sm font-medium text-orange-400">{{ auth()->user()->name }} (Ketua Tim)</span>
                        </label>

                        @if(!empty($daftarAnggotaArray))
                            @foreach($daftarAnggotaArray as $anggota)
                                @if(trim($anggota) !== '')
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ trim($anggota) }}" x-model="anggotaHadir" class="rounded border-zinc-700 text-orange-600 focus:ring-orange-500 bg-zinc-900 cursor-pointer">
                                    <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition">{{ trim($anggota) }}</span>
                                </label>
                                @endif
                            @endforeach
                        @else
                            <p class="text-xs text-red-500 italic mt-2">Aset tidak menugaskan anggota tambahan.</p>
                        @endif
                    </div>
                </div>

                <hr class="border-zinc-800">
                
                <h3 class="font-bold text-white text-lg">Dokumentasi Lapangan (QC)</h3>
                <p class="text-xs text-zinc-400 -mt-4 mb-2">Unggah foto sesuai instruksi Aset. Pastikan timestamp kamera menyala.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($kebutuhanFoto as $index => $namaFoto)
                        <div class="bg-zinc-950 p-3 rounded-xl border border-zinc-800">
                            <label class="block text-xs font-bold text-emerald-400 mb-2">{{ $index + 1 }}. {{ $namaFoto }} *</label>
                            
                            <div class="relative w-full h-32 border-2 border-dashed border-zinc-700 rounded-lg bg-zinc-900 hover:bg-zinc-800 transition flex items-center justify-center overflow-hidden cursor-pointer" @click="$refs['inputFoto_{{ $index }}'].click()">
                                
                                <div x-show="!previews[{{ $index }}]" class="text-center pointer-events-none">
                                    <svg class="w-6 h-6 text-zinc-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px] font-bold text-zinc-500">Ambil Foto</span>
                                </div>
                                
                                <img x-show="previews[{{ $index }}]" :src="previews[{{ $index }}]" class="absolute inset-0 w-full h-full object-cover" style="display: none;">
                                
                                <input type="file" name="foto_dinamis[]" x-ref="inputFoto_{{ $index }}" accept="image/*" class="hidden" @change="previews[{{ $index }}] = URL.createObjectURL($event.target.files[0])" required>
                                <input type="hidden" name="nama_foto_dinamis[]" value="{{ $namaFoto }}">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <label class="block text-sm font-bold text-zinc-300 mb-2">📝 Catatan Lapangan / Kendala (Opsional)</label>
                    <textarea name="catatan_lapangan" rows="3" placeholder="Contoh: Baut lampu sorot sebelah kiri harus diganti bulan depan." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-white text-sm focus:border-orange-500 outline-none"></textarea>
                </div>
            </div>

            <button type="submit" class="mt-8 w-full bg-orange-600 hover:bg-orange-700 text-white font-black uppercase tracking-wider py-4 rounded-xl shadow-[0_0_20px_rgba(234,88,12,0.3)] transition active:scale-95">
                Kirim Laporan QC
            </button>
        </form>
    </div>
</x-boma-layout>