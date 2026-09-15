<x-boma-layout>
    <div class="pb-10 flex flex-col space-y-6">
        
        <!-- Peringatan Sukses / Error -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-5 py-4 rounded-xl text-sm font-bold flex items-center shadow-lg">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white mb-1">Brankas Arsip Internal Memo (IM) 🗄️</h1>
                <p class="text-sm text-zinc-400">Daftar seluruh surat tugas operasional beserta hasil pengerjaan lapangannya.</p>
            </div>
            
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <form method="GET" action="{{ route('aset.im') }}" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No IM, Klien..." class="w-full bg-zinc-900 border border-zinc-800 rounded-lg pl-9 pr-3 py-2.5 text-white text-xs focus:border-red-500 outline-none">
                    <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
                
                <a href="{{ route('aset.im.buat') }}" class="bg-red-600 hover:bg-red-500 text-white text-xs font-bold px-4 py-2.5 rounded-lg flex items-center shadow-lg transition whitespace-nowrap">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat IM Baru
                </a>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($listIm as $im)
                @php
                    $titikArray = json_decode($im->kode_titik, true);
                    $titikRapi = is_array($titikArray) ? implode(' | ', $titikArray) : $im->kode_titik;
                    
                    $fotoKerja = $im->foto_hasil ? json_decode($im->foto_hasil, true) : [];
                    $fotoKlienSebelumnya = $im->foto_klien ? json_decode($im->foto_klien, true) : []; // Menarik data foto yang sudah dikirim ke klien
                    
                    $catatanAsli = $im->catatan ?: '';
                    $daftarAnggota = '-';
                    $instruksiKhusus = '-';

                    if (strpos($catatanAsli, '|') !== false) {
                        $pecahan = explode('|', $catatanAsli, 2); 
                        $bagianAnggota = trim($pecahan[0]); 
                        $bagianInstruksi = trim($pecahan[1]);

                        $daftarAnggota = str_replace('Daftar_Anggota: ', '', $bagianAnggota);
                        $instruksiKhusus = $bagianInstruksi ?: '-';
                    } else {
                        $instruksiKhusus = $catatanAsli ?: '-';
                    }
                @endphp

                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-xl transition-all duration-300">
                    
                    <!-- HEADER FOLDER -->
                    <div onclick="toggleFolder('{{ $im->id }}')" class="p-5 cursor-pointer hover:bg-zinc-800/50 transition flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                        
                        <div class="flex items-center space-x-4 w-full md:w-1/4">
                            <div class="bg-zinc-950 p-2.5 rounded-xl border border-zinc-800 flex-shrink-0 text-red-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-red-500 font-bold text-sm leading-tight">{{ $im->no_im }}</h3>
                                <p class="text-[10px] text-zinc-500 mt-0.5">Tgl Terbit: {{ \Carbon\Carbon::parse($im->created_at)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="w-full md:w-1/4">
                            <p class="text-xs text-zinc-400 font-medium">{{ $im->tujuan }}</p>
                            <p class="text-[10px] text-zinc-600 mt-0.5">{{ $im->diterbitkan_oleh }} (Penerbit)</p>
                        </div>

                        <div class="w-full md:w-2/4">
                            <p class="text-xs text-white font-bold bg-zinc-800 px-2 py-1 rounded inline-block truncate max-w-[200px] md:max-w-xs">{{ $titikRapi }}</p>
                            <p class="text-[10px] text-zinc-400 mt-1">{{ $im->perihal }}</p>
                        </div>

                        <div class="flex-shrink-0 ml-auto flex items-center space-x-3">
                            <!-- Status Pengerjaan -->
                            @if($im->status_laporan === 'Disetujui')
                                <span class="bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 px-2 py-0.5 rounded text-[10px] font-bold">Selesai Dikerjakan</span>
                            @elseif($im->status_laporan === 'Menunggu Validasi')
                                <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded text-[10px] font-bold">Menunggu Validasi QC</span>
                            @else
                                <span class="bg-orange-500/10 text-orange-400 border border-orange-500/20 px-2 py-0.5 rounded text-[10px] font-bold">Belum Dikerjakan</span>
                            @endif

                            <svg id="icon-arrow-{{ $im->id }}" class="w-4 h-4 text-zinc-500 group-hover:text-white transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- KONTEN RINCIAN -->
                    <div id="folder-content-{{ $im->id }}" class="hidden border-t border-zinc-800 bg-zinc-950/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                            
                            <!-- KIRI: RINCIAN SURAT IM -->
                            <div class="p-6 border-r border-zinc-800">
                                <h4 class="text-xs font-bold text-red-500 uppercase tracking-wider mb-4 border-b border-zinc-800 pb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Rincian Surat Perintah
                                </h4>
                                
                                <div class="space-y-4 text-sm text-zinc-300">
                                    <div>
                                        <span class="text-zinc-500 block text-[10px]">Klien & Pekerjaan Visual:</span> 
                                        <span class="font-bold">{{ $im->klien_visual }}</span>
                                    </div>
                                    <div>
                                        <span class="text-zinc-500 block text-[10px]">Tanggal Eksekusi (Target):</span> 
                                        <span class="text-emerald-400 font-bold">{{ \Carbon\Carbon::parse($im->tanggal_target)->format('d M Y') }}</span>
                                    </div>
                                    
                                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden mt-2">
                                        <div class="p-3 border-b border-zinc-800 bg-zinc-900/50">
                                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Daftar Anggota Tim (Bertugas):</span>
                                            <p class="text-xs text-zinc-300 font-medium leading-relaxed">{{ $daftarAnggota }}</p>
                                        </div>
                                        <div class="p-3">
                                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Catatan Tambahan (Instruksi Lapangan):</span>
                                            <p class="text-xs text-yellow-500 italic leading-relaxed">{{ $instruksiKhusus }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3">
                                        <form action="{{ route('aset.im.destroy', $im->id) }}" method="POST" onsubmit="return confirm('Peringatan: Menghapus IM ini akan menghilangkan riwayat tugas dari sistem. Yakin ingin menghapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-white bg-red-500/10 hover:bg-red-500 px-3 py-1.5 rounded transition">
                                                Tarik / Hapus IM
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- KANAN: HASIL PENGERJAAN PRODUKSI -->
                            <div class="p-6">
                                <h4 class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-4 border-b border-zinc-800 pb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Hasil Pekerjaan Lapangan
                                </h4>

                                @if($im->tanggal_dikerjakan)
                                    <div class="mb-4">
                                        <div class="text-[10px] text-zinc-500 uppercase tracking-widest mb-1">Dikerjakan & Dilaporkan Oleh:</div>
                                        <div class="flex items-center text-sm font-bold text-white">
                                            <div class="w-6 h-6 bg-emerald-500/20 text-emerald-500 rounded-full flex items-center justify-center mr-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            Tim Produksi / Lapangan
                                            <span class="ml-2 text-[10px] text-zinc-500 font-normal">({{ \Carbon\Carbon::parse($im->tanggal_dikerjakan)->format('d M Y - H:i') }} WIB)</span>
                                        </div>
                                    </div>

                                    @if(count($fotoKerja) > 0)
                                        
                                        <!-- FORM UPDATE FOTO KLIEN -->
                                        <form action="{{ route('aset.validasi.foto_klien', $im->laporan_id) }}" method="POST" x-data="{ 
                                            fotoTerpilih: {{ json_encode($fotoKlienSebelumnya) }},
                                            isEditing: false
                                        }">
                                            @csrf
                                            
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="text-[10px] text-zinc-500 uppercase tracking-widest">Dokumentasi Foto Kerja:</div>
                                                
                                                <!-- Indikator Status & Tombol Edit -->
                                                <div class="flex items-center space-x-2">
                                                    @if($im->dibagikan_ke_klien)
                                                        <span class="bg-blue-500/10 text-blue-400 text-[9px] font-bold px-2 py-0.5 rounded shadow-sm">Telah Dibagikan ke Klien</span>
                                                    @endif
                                                    
                                                    <button type="button" @click="isEditing = !isEditing" class="text-[10px] font-bold text-zinc-400 hover:text-white underline decoration-zinc-600 transition" x-text="isEditing ? 'Batal Edit' : 'Edit Foto Klien'"></button>
                                                </div>
                                            </div>

                                            <!-- Galeri Foto (Interactive dengan Checkbox) -->
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 relative">
                                                
                                                <!-- Overlay gelap jika bukan mode edit -->
                                                <div x-show="!isEditing" class="absolute inset-0 z-10 cursor-pointer" @click="isEditing = true"></div>

                                                @foreach($fotoKerja as $foto)
                                                    <label class="relative group w-full select-none" :class="isEditing ? 'cursor-pointer' : 'cursor-default'">
                                                        <input type="checkbox" name="foto_klien[]" value="{{ $foto['path'] }}" x-model="fotoTerpilih" class="hidden">
                                                        
                                                        <div class="block overflow-hidden rounded-xl border-2 transition-all duration-200" 
                                                             :class="{
                                                                'border-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)]': fotoTerpilih.includes('{{ $foto['path'] }}'),
                                                                'border-zinc-700 hover:border-zinc-500': isEditing && !fotoTerpilih.includes('{{ $foto['path'] }}'),
                                                                'border-zinc-800 opacity-50 grayscale': !isEditing && !fotoTerpilih.includes('{{ $foto['path'] }}')
                                                             }">
                                                            <img src="{{ asset('storage/' . $foto['path']) }}" class="w-full h-24 object-cover">
                                                            
                                                            <!-- Checkmark Overlay -->
                                                            <div x-show="fotoTerpilih.includes('{{ $foto['path'] }}')" class="absolute inset-0 bg-emerald-500/20 flex items-center justify-center">
                                                                <div class="bg-emerald-500 text-white rounded-full p-1 shadow-xl transform scale-90">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="text-[9px] text-zinc-400 mt-1 font-bold text-center truncate px-1" title="{{ $foto['kategori'] }}">{{ $foto['kategori'] }}</p>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <!-- Tombol Submit (Hanya tampil saat mode Edit) -->
                                            <div x-show="isEditing" x-collapse class="mt-4 flex justify-end">
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2.5 rounded-lg flex items-center shadow-lg transition">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                    Update Foto Klien
                                                </button>
                                            </div>
                                        </form>

                                    @else
                                        <div class="p-4 bg-zinc-900 border border-dashed border-zinc-700 rounded-lg text-center">
                                            <p class="text-xs text-zinc-500">Laporan disubmit tanpa dokumentasi foto.</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="h-32 flex flex-col items-center justify-center border border-dashed border-zinc-800 rounded-xl bg-zinc-900/50">
                                        <svg class="w-8 h-8 text-zinc-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-xs text-zinc-500 font-medium">Tim Produksi belum menyerahkan Laporan QC.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-10 text-center flex flex-col items-center justify-center shadow-xl">
                    <svg class="w-16 h-16 text-zinc-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-white font-bold text-lg mb-1">Brankas IM Kosong</h3>
                    <p class="text-sm text-zinc-500">Belum ada surat perintah kerja yang diterbitkan atau ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SCRIPT SEDERHANA BUKA-TUTUP FOLDER -->
    <script>
        function toggleFolder(id) {
            let content = document.getElementById('folder-content-' + id);
            let arrow = document.getElementById('icon-arrow-' + id);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }
    </script>
</x-boma-layout>