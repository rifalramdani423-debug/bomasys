<x-boma-layout>
    <div class="pb-10" x-data="imBuilder()">
        <div class="mb-6">
            <h1 class="text-2xl font-black text-white mb-1">Penerbitan Internal Memo (IM) / SPK 📋</h1>
            <p class="text-sm text-zinc-400">Buat surat perintah kerja untuk Tim Produksi atau Admin Pajak. Data akan disimpan ke arsip dan dikirim ke produksi.</p>
        </div>

        <form action="{{ route('aset.im.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                <!-- FORM INPUT ASET -->
                <div class="xl:col-span-5 space-y-6">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl sticky top-6">
                        <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2">Formulir IM</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1">Nomor IM Otomatis</label>
                                <input type="text" name="no_im" x-model="noIm" readonly class="w-full bg-zinc-950/50 border border-zinc-800 rounded-lg px-3 py-2 text-zinc-500 font-bold text-sm">
                            </div>
                            
                            <!-- Pilih Ketua Tim -->
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1">Ketua Tim Bertanggung Jawab *</label>
                                <select name="tujuan" @change="kepada = $event.target.options[$event.target.selectedIndex].getAttribute('data-nama')" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg p-2.5 text-white focus:border-red-500 outline-none">
                                    <option value="" disabled selected>-- Pilih Ketua Tim Lapangan --</option>
                                    @foreach($ketuaTimList as $ketua)
                                        <option value="{{ $ketua->id }}" data-nama="{{ $ketua->name }}">Tim Lapangan: {{ $ketua->name }}</option> 
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pilih Anggota Tim (Baru) -->
                            <div class="pt-2 border-t border-zinc-800">
                                <label class="block text-xs font-bold text-zinc-400 mb-2">Pilih Anggota Tim Lapangan (Centang) *</label>
                                <div class="bg-zinc-950 border border-zinc-800 rounded-lg p-3 max-h-40 overflow-y-auto custom-scrollbar space-y-2">
                                    @foreach($anggotaTimList as $anggota)
                                        @php
                                            // Cek apakah nama anggota ini ada di dalam daftar pekerja sibuk
                                            $isSibuk = in_array($anggota->name, $pekerjaSibuk);
                                        @endphp
                                        
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" 
                                                name="anggota_pilihan[]" 
                                                value="{{ $anggota->name }}" 
                                                class="rounded border-zinc-700 bg-zinc-900"
                                                {{ $isSibuk ? 'disabled' : '' }}> <label class="ml-2 text-sm {{ $isSibuk ? 'text-zinc-600 line-through' : 'text-zinc-300' }}">
                                                {{ $anggota->name }} 
                                                
                                                @if($isSibuk) 
                                                    <span class="text-[10px] text-red-500 ml-1 font-bold">(Sedang Bertugas)</span> 
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1">Perihal Utama *</label>
                                <select name="perihal" x-model="perihal" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                    <option value="Pemasangan Visual Baru">Pemasangan Visual Baru</option>
                                    <option value="Pengecekan Kesiapan H-3">Pengecekan Kesiapan H-3</option>
                                    <option value="Maintenance / Perawatan Bulanan">Maintenance / Perawatan Bulanan</option>
                                    <option value="Pelepasan Visual (Turun Tayang)">Pelepasan Visual (Turun Tayang)</option>
                                </select>
                            </div>
                            
                            <div class="pt-2 border-t border-zinc-800">
                                <label class="block text-xs font-bold text-zinc-400 mb-1">Klien & Visual *</label>
                                <input type="text" name="klien_visual" x-model="visual" placeholder="Contoh: PT Antam / Visual Emas" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 mb-4">
                                
                                <label class="block text-xs font-bold text-zinc-400 mb-2">Pilih Lokasi Titik Reklame (Bisa Lebih Dari Satu) *</label>
                                <div class="bg-zinc-950 border border-zinc-800 rounded-lg p-3 max-h-40 overflow-y-auto custom-scrollbar mb-4 space-y-2">
                                    @foreach($semuaBillboard as $bb)
                                        <label class="flex items-start space-x-3 cursor-pointer group">
                                            <input type="checkbox" name="kode_titik[]" value="{{ $bb->kode_titik }} - {{ $bb->lokasi }}" @change="updateTitikTerpilih" class="mt-0.5 rounded border-zinc-700 text-red-600 focus:ring-red-500 bg-zinc-900 cursor-pointer checkbox-titik" {{ (in_array($bb->kode_titik, $titikList ?? [])) ? 'checked' : '' }}>
                                            <div>
                                                <span class="block text-xs font-bold text-zinc-300 group-hover:text-white transition">{{ $bb->kode_titik }}</span>
                                                <span class="block text-[10px] text-zinc-500">{{ $bb->lokasi }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                
                                <label class="block text-xs font-bold text-zinc-400 mb-1">Tanggal Eksekusi Target *</label>
                                <input type="date" name="tanggal_target" x-model="tglEksekusi" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 [color-scheme:dark]">
                            </div>

                            <div class="pt-2 border-t border-zinc-800">
                                <label class="block text-xs font-bold text-emerald-400 mb-2">Daftar Request Bukti Foto (Bisa Ditambah) *</label>
                                
                                <div class="space-y-2 mb-2">
                                    <template x-for="(foto, index) in daftarFoto" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <input type="text" x-model="daftarFoto[index]" name="kebutuhan_foto[]" placeholder="Contoh: Foto Siang, Foto Kelistrikan..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-xs focus:border-emerald-500 outline-none" required>
                                            
                                            <button type="button" @click="hapusFoto(index)" class="text-zinc-600 hover:text-red-500 p-2 transition" title="Hapus Kolom">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                
                                <button type="button" @click="tambahFoto()" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center mb-4 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Permintaan Foto
                                </button>

                                <label class="block text-xs font-bold text-zinc-400 mb-1">Catatan Khusus Lapangan</label>
                                <textarea name="catatan" x-model="catatan" rows="3" placeholder="Contoh: Jangan lupa bawa tangga lipat." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 outline-none"></textarea>
                            </div>
                        </div>

                        <!-- TOMBOL SUBMIT -->
                        <button type="submit" class="mt-6 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-red-600/20 flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Kirim IM ke Produksi
                        </button>
                    </div>
                </div>

                <!-- PREVIEW KERTAS IM (LIVE) -->
                <div class="xl:col-span-7 overflow-x-auto bg-zinc-800/30 p-4 md:p-8 rounded-2xl flex justify-center items-start">
                    <div id="kertas-im" class="bg-white text-black w-[210mm] min-h-[297mm] shadow-2xl p-[20mm] text-sm relative font-sans">
                        
                        <div class="border-b-2 border-red-600 pb-4 mb-6 flex justify-between items-center">
                            <div>
                                <h1 class="text-3xl font-light text-zinc-400">boma<span class="font-bold text-red-600">dvertising</span></h1>
                            </div>
                            <div class="text-right">
                                <h2 class="text-xl font-black uppercase tracking-widest text-zinc-800">Internal Memo</h2>
                                <p class="text-xs text-zinc-500 font-bold mt-1">No: <span x-text="noIm"></span></p>
                            </div>
                        </div>

                        <table class="w-full mb-8 text-sm">
                            <tr>
                                <td class="w-32 font-bold py-1">Tanggal Terbit</td>
                                <td class="w-4 py-1">:</td>
                                <td class="py-1" x-text="formatTanggal(new Date())"></td>
                            </tr>
                            <tr>
                                <td class="font-bold py-1">Kepada</td>
                                <td>:</td>
                                <td class="py-1 uppercase font-bold text-blue-800" x-text="kepada || '....................'"></td>
                            </tr>
                            <tr>
                                <td class="font-bold py-1 align-top">Anggota Tim</td>
                                <td class="align-top">:</td>
                                <td class="py-1 text-xs text-gray-700 leading-tight" x-text="daftarAnggota || '....................'"></td>
                            </tr>
                            <tr>
                                <td class="font-bold py-1 pt-3">Perihal</td>
                                <td class="pt-3">:</td>
                                <td class="py-1 pt-3 font-bold text-red-600" x-text="perihal"></td>
                            </tr>
                        </table>

                        <div class="border-t border-black pt-4 min-h-[120mm]">
                            <h3 class="font-bold mb-3 underline">Instruksi / Informasi :</h3>
                            
                            <table class="w-full ml-4 mb-6 text-sm">
                                <tr>
                                    <td class="w-40 py-1 align-top">- Pekerjaan Visual</td>
                                    <td class="w-4 py-1 align-top">:</td>
                                    <td class="py-1 font-bold align-top" x-text="visual || '....................'"></td>
                                </tr>
                                <tr>
                                    <td class="py-1 align-top">- Lokasi & Alamat</td>
                                    <td class="py-1 align-top">:</td>
                                    <td class="py-1 font-bold text-red-600 align-top">
                                        <ul class="list-disc pl-4 space-y-1" x-html="daftarTitikHtml || '<li>....................</li>'"></ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1">- Tanggal Eksekusi</td>
                                    <td class="py-1">:</td>
                                    <td class="py-1 font-bold" x-text="formatTanggal(tglEksekusi) || '....................'"></td>
                                </tr>
                            </table>

                            <h3 class="font-bold mb-2">Catatan Tambahan Lapangan :</h3>
                            <div class="ml-4 whitespace-pre-wrap leading-relaxed mb-6" x-text="catatan || '-'"></div>

                            <div class="bg-gray-100 p-3 rounded border border-gray-300">
                                <h3 class="font-bold mb-1 text-xs text-blue-800">* Bukti Foto Laporan QC yang wajib diunggah:</h3>
                                <ul class="list-disc pl-4 text-xs text-gray-700">
                                    <template x-for="foto in daftarFoto">
                                        <li x-text="foto"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="absolute bottom-[20mm] left-[20mm] right-[20mm]">
                            <table class="w-full text-center border-collapse border border-black text-xs">
                                <tr class="bg-gray-100">
                                    <td class="border border-black py-2 font-bold w-1/3">Dikirim oleh :</td>
                                    <td class="border border-black py-2 font-bold w-1/3">Disetujui oleh :</td>
                                    <td class="border border-black py-2 font-bold w-1/3">Diterima oleh :</td>
                                </tr>
                                <tr>
                                    <td class="border border-black h-24 align-bottom pb-2">
                                        <p class="font-bold underline">Aset Dept</p>
                                    </td>
                                    <td class="border border-black h-24 align-bottom pb-2">
                                        <p class="font-bold underline">Management</p>
                                    </td>
                                    <td class="border border-black h-24 align-bottom pb-2">
                                        <p class="font-bold underline uppercase" x-text="kepada || 'KETUA TIM'"></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('imBuilder', () => {
                let kodeJenis = '{{ $jenis ?? '' }}';
                let defaultPerihal = 'Pemasangan Visual Baru';
                if(kodeJenis === 'CHK') defaultPerihal = 'Pengecekan Kesiapan H-3';
                if(kodeJenis === 'MNT') defaultPerihal = 'Maintenance / Perawatan Bulanan';

                return {
                    noIm: '{{ $noIm ?? "" }}',
                    kepada: '',
                    perihal: defaultPerihal,
                    visual: '{{ $klienVisualDefault ?? "" }}',
                    tglEksekusi: '{{ $tglEksekusiDefault ?? date("Y-m-d") }}',
                    catatan: '',
                    daftarTitikHtml: '',
                    daftarAnggota: '', // Untuk preview kertas
                    
                    daftarFoto: ['Foto Kondisi Siang', 'Foto Kondisi Malam (Lampu)'],

                    tambahFoto() {
                        this.daftarFoto.push('');
                    },
                    hapusFoto(index) {
                        if(this.daftarFoto.length > 1) {
                            this.daftarFoto.splice(index, 1);
                        } else {
                            alert('Minimal harus ada 1 permintaan foto!');
                        }
                    },

                    init() {
                        setTimeout(() => { this.updateTitikTerpilih(); }, 100);
                    },

                    updateTitikTerpilih() {
                        let checkboxes = document.querySelectorAll('.checkbox-titik:checked');
                        let html = '';
                        checkboxes.forEach((cb) => {
                            html += `<li>${cb.value}</li>`;
                        });
                        this.daftarTitikHtml = html;
                    },

                    // Fungsi baru untuk update teks daftar anggota di preview
                    updateAnggotaTerpilih() {
                        let checkboxes = document.querySelectorAll('.checkbox-anggota:checked');
                        let arr = [];
                        checkboxes.forEach((cb) => {
                            arr.push(cb.value);
                        });
                        this.daftarAnggota = arr.join(', ');
                    },

                    formatTanggal(dateString) {
                        if(!dateString) return '';
                        let d = new Date(dateString);
                        let bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
                    }
                }
            });
        });
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #18181b; border-radius: 8px;}
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 8px; }
    </style>
</x-boma-layout>