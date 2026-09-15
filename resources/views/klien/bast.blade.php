<x-boma-layout>
    <div class="pb-10" x-data="bastBuilder()">
        
        <div class="mb-6">
            <h1 class="text-2xl font-black text-white mb-1">Penerbitan BAST 🤝</h1>
            <p class="text-sm text-zinc-400">Buat Berita Acara Serah Terima Pekerjaan untuk pengajuan yang telah selesai dipasang.</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            <!-- ==========================================
                 KOLOM KIRI: FORM INPUT BAST (4 Kolom)
            =========================================== -->
            <div class="xl:col-span-4 space-y-6">
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl sticky top-6">
                    <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2">Form Data BAST</h3>
                    
                    <div class="space-y-4">
                        <!-- Data Surat -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">Nomor Surat BAST *</label>
                            <input type="text" x-model="noSurat" placeholder="Contoh: 004/BCC-BASTP/I/2026" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">Tanggal BAST *</label>
                            <input type="date" x-model="tanggalBast" @change="formatTanggalTerbilang()" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 [color-scheme:dark]">
                        </div>

                        <div class="border-t border-zinc-800 pt-4">
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">Naskah / Visual Reklame *</label>
                            <input type="text" x-model="naskahVisual" placeholder="Contoh: Grab / Inza / Indomie" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        </div>

                        <!-- Data Pihak Pertama (Boma) -->
                        <div class="border-t border-zinc-800 pt-4">
                            <h4 class="text-xs font-black text-red-500 mb-2">PIHAK PERTAMA (BOMA)</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[10px] text-zinc-500 mb-1">Nama Perwakilan</label>
                                    <input type="text" x-model="pihak1Nama" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-zinc-500 mb-1">Bertindak Atas Nama</label>
                                    <input type="text" x-model="pihak1Instansi" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                </div>
                            </div>
                        </div>

                        <!-- Data Pihak Kedua (Klien) - Otomatis dari Database -->
                        <div class="border-t border-zinc-800 pt-4">
                            <h4 class="text-xs font-black text-blue-500 mb-2">PIHAK KEDUA (KLIEN)</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[10px] text-zinc-500 mb-1">Nama Perwakilan</label>
                                    <input type="text" x-model="pihak2Nama" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-zinc-500 mb-1">Bertindak Atas Nama</label>
                                    <input type="text" x-model="pihak2Instansi" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button @click="cetakPDF" class="mt-6 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-lg flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Simpan & Cetak BAST
                    </button>
                </div>
            </div>

            <!-- ==========================================
                 KOLOM KANAN: LIVE PREVIEW KERTAS A4 (8 Kolom)
            =========================================== -->
            <div class="xl:col-span-8 overflow-x-auto bg-zinc-800/30 p-4 md:p-8 rounded-2xl flex justify-center items-start">
                
                <!-- KERTAS BAST -->
                <!-- Font serif digunakan agar sesuai dengan dokumen resmi di gambar -->
                <div id="kertas-bast" class="bg-white text-black w-[210mm] min-h-[297mm] shadow-2xl p-[20mm] text-[14px] leading-relaxed relative font-serif">
                    
                    <!-- KOP JUDUL -->
                    <div class="text-center mb-8">
                        <h2 class="text-lg font-bold underline decoration-2 uppercase tracking-wide">BERITA ACARA SERAH TERIMA PEKERJAAN</h2>
                        <p class="mt-1 font-medium text-sm" x-text="noSurat || '.../BCC-BASTP/.../2026'"></p>
                    </div>

                    <!-- PARAGRAF PEMBUKA -->
                    <div class="mb-6 text-justify">
                        <p>
                            Pada hari ini <em x-text="hariTerbilang"></em> tanggal <em x-text="tglTerbilang"></em> 
                            Bulan <em x-text="bulanTerbilang"></em> tahun <em x-text="tahunTerbilang"></em> 
                            (<span x-text="formatTanggalAngka(tanggalBast)"></span>), yang bertanda tangan dibawah ini :
                        </p>
                    </div>

                    <!-- PIHAK-PIHAK -->
                    <div class="mb-6 space-y-4 ml-2">
                        <!-- PIHAK 1 -->
                        <table class="w-full align-top">
                            <tr>
                                <td class="w-[25px] align-top">1.</td>
                                <td class="w-[70px] align-top">Nama</td>
                                <td class="w-[10px] align-top">:</td>
                                <td class="align-top font-bold" x-text="pihak1Nama"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-justify pt-1">
                                    Dalam hal ini bertindak untuk dan atas nama <strong><span x-text="pihak1Instansi"></span></strong> 
                                    yang beralamat di Komplek Alamanda Dago Permai kav. D 44 Kota Bandung. selanjutnya disebut sebagai <strong>PIHAK PERTAMA.</strong>
                                </td>
                            </tr>
                        </table>

                        <!-- PIHAK 2 -->
                        <table class="w-full align-top">
                            <tr>
                                <td class="w-[25px] align-top">2.</td>
                                <td class="w-[70px] align-top">Nama</td>
                                <td class="w-[10px] align-top">:</td>
                                <td class="align-top font-bold" x-text="pihak2Nama"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-justify pt-1">
                                    Dalam hal ini bertindak untuk dan atas nama <strong><span x-text="pihak2Instansi"></span></strong> 
                                    yang beralamat di <span x-text="pihak2Alamat"></span>. selanjutnya disebut sebagai <strong>PIHAK KEDUA.</strong>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- KETERANGAN PEKERJAAN -->
                    <div class="mb-4 text-justify">
                        <p><strong>PIHAK PERTAMA</strong> telah melaksanakan pekerjaan penempatan media luar ruang billboard dengan data sebagai berikut :</p>
                    </div>

                    <!-- TABEL PEKERJAAN -->
                    <table class="w-full border-collapse border border-black mb-6 text-sm text-center">
                        <thead class="font-bold">
                            <tr>
                                <td class="border border-black p-2 w-[40px]">No</td>
                                <td class="border border-black p-2">Lokasi</td>
                                <td class="border border-black p-2 w-[80px]">Ukuran</td>
                                <td class="border border-black p-2">Naskah</td>
                                <td class="border border-black p-2 w-[140px]">Periode Sewa</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- LOOPING DATA TITIK DARI DATABASE (Contoh 1 Baris) -->
                            @if(isset($pengajuan) && $pengajuan->details->count() > 0)
                                @foreach($pengajuan->details as $index => $detail)
                                    @php
                                        $infoBillboard = \Illuminate\Support\Facades\DB::table('billboards')->where('kode_titik', $detail->kode_titik)->first();
                                    @endphp
                                    <tr>
                                        <td class="border border-black p-2 align-middle">{{ $index + 1 }}</td>
                                        <td class="border border-black p-2 align-middle text-left leading-tight">
                                            {{ $infoBillboard->lokasi ?? 'Lokasi tidak ditemukan' }}
                                        </td>
                                        <td class="border border-black p-2 align-middle">{{ $infoBillboard->ukuran ?? '-' }}<br><span class="text-xs">V-FL</span></td>
                                        <td class="border border-black p-2 align-middle font-semibold" x-text="naskahVisual"></td>
                                        <td class="border border-black p-2 align-middle text-xs leading-tight">
                                            {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->translatedFormat('d F Y') }}<br>s.d<br>{{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->translatedFormat('d F Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <!-- Dummy Row untuk Preview jika belum ada data -->
                                <tr>
                                    <td class="border border-black p-2 align-middle">1</td>
                                    <td class="border border-black p-2 align-middle text-left leading-tight">Jl. Dipatiukur, Kota Bandung (View menuju Monumen Perjuangan)</td>
                                    <td class="border border-black p-2 align-middle">4m x 8m<br><span class="text-xs">V-FL</span></td>
                                    <td class="border border-black p-2 align-middle font-semibold" x-text="naskahVisual"></td>
                                    <td class="border border-black p-2 align-middle text-xs leading-tight">12 Januari 2026<br>s.d<br>11 Januari 2027</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- KESIMPULAN HASIL -->
                    <div class="mb-8">
                        <p class="mb-2">Dengan hasil sebagai berikut :</p>
                        <ol class="list-decimal pl-5 space-y-1 text-justify">
                            <li><strong>PIHAK PERTAMA</strong> telah selesai melaksanakan pekerjaan penempatan media luar ruang billboard dengan baik.</li>
                            <li><strong>PIHAK PERTAMA</strong> menyerahkan hasil pekerjaan tersebut kepada <strong>PIHAK KEDUA</strong> dan <strong>PIHAK KEDUA</strong> menerima dengan baik pekerjaan tersebut dari <strong>PIHAK PERTAMA.</strong></li>
                            <li>Melampirkan dokumentasi visual.</li>
                        </ol>
                    </div>

                    <!-- PENUTUP -->
                    <div class="mb-12 text-justify">
                        <p>Demikian Berita Acara ini dibuat dan ditanda tangani oleh kedua belah pihak pada hari, tanggal, dan tahun tersebut diatas.</p>
                    </div>

                    <!-- TANDA TANGAN -->
                    <div class="flex justify-between text-center mt-10 px-8">
                        <div class="w-1/2">
                            <p class="mb-20">PIHAK PERTAMA</p>
                            <!-- Space for signature -->
                            <p class="font-bold underline" x-text="pihak1Nama"></p>
                        </div>
                        <div class="w-1/2">
                            <p class="mb-20">PIHAK KEDUA</p>
                            <!-- Space for signature -->
                            <p class="font-bold underline" x-text="pihak2Nama"></p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT ALPINE.JS -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bastBuilder', () => ({
                noSurat: '',
                tanggalBast: new Date().toISOString().split('T')[0],
                naskahVisual: 'Visual Default',
                
                // Data Boma (Bisa disesuaikan dengan profil pimpinan Boma)
                pihak1Nama: 'Lesmono Hendra Kusumah',
                pihak1Instansi: 'CV. Boma Cipta Citra',
                
                // Data Klien (Asumsi dilempar dari controller via variable $pengajuan->user)
                pihak2Nama: '{{ $pengajuan->user->name ?? "Nama Klien" }}',
                pihak2Instansi: '{{ $pengajuan->user->nama_perusahaan ?? "Perusahaan Klien" }}',
                pihak2Alamat: '{{ $pengajuan->user->alamat ?? "Jl. Soekarno Hatta No. 676 Kota Bandung" }}',

                // Variabel Terbilang (Digenerate otomatis)
                hariTerbilang: '',
                tglTerbilang: '',
                bulanTerbilang: '',
                tahunTerbilang: '',

                init() {
                    this.formatTanggalTerbilang();
                },

                formatTanggalAngka(dateString) {
                    if(!dateString) return '';
                    let d = new Date(dateString);
                    return ('0' + d.getDate()).slice(-2) + ' - ' + ('0' + (d.getMonth() + 1)).slice(-2) + ' - ' + d.getFullYear();
                },

                formatTanggalTerbilang() {
                    if(!this.tanggalBast) return;
                    let d = new Date(this.tanggalBast);
                    
                    const namaHari = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
                    const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    
                    // Simple logic konversi angka ke terbilang (cukup untuk tanggal 1-31 dan tahun 2020-2030an)
                    const angkaKeKata = (angka) => {
                        const kata = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
                        if(angka < 12) return kata[angka];
                        if(angka < 20) return kata[angka - 10] + ' belas';
                        if(angka < 100) return kata[Math.floor(angka / 10)] + ' puluh ' + kata[angka % 10];
                        if(angka < 200) return 'seratus ' + angkaKeKata(angka - 100);
                        if(angka < 1000) return kata[Math.floor(angka / 100)] + ' ratus ' + angkaKeKata(angka % 100);
                        if(angka < 2000) return 'seribu ' + angkaKeKata(angka - 1000);
                        if(angka < 10000) return kata[Math.floor(angka / 1000)] + ' ribu ' + angkaKeKata(angka % 1000);
                        return angka.toString(); // Fallback
                    };

                    this.hariTerbilang = namaHari[d.getDay()];
                    this.tglTerbilang = angkaKeKata(d.getDate());
                    this.bulanTerbilang = namaBulan[d.getMonth()];
                    
                    // Format khusus tahun agar "dua ribu dua puluh enam"
                    let tahun = d.getFullYear();
                    this.tahunTerbilang = angkaKeKata(tahun);
                },

                cetakPDF() {
                    if(!this.noSurat || !this.naskahVisual) {
                        alert('Harap isi Nomor Surat dan Naskah Visual terlebih dahulu!');
                        return;
                    }
                    
                    let printContents = document.getElementById('kertas-bast').innerHTML;
                    let originalContents = document.body.innerHTML;

                    // Buat styling cetak khusus agar background merah/gelap tidak ikut tercetak
                    document.body.innerHTML = `
                        <div style="padding:0; margin:0; width:100%; height:100%; background: white;">
                            ${printContents}
                        </div>
                    `;
                    window.print();
                    document.body.innerHTML = originalContents;
                    window.location.reload(); 
                }
            }));
        });
    </script>
</x-boma-layout>