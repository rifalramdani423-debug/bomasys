<x-boma-layout>
    <div class="pb-10" x-data="bastBuilder()">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-5 py-4 rounded-xl text-sm font-bold flex items-center mb-6 shadow-lg">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        
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
                        <!-- Pilih Pesanan / Klien dengan Status Pembayaran -->
                        <div class="bg-red-950/30 p-3 rounded-xl border border-red-900/50 mb-4">
                            <label class="block text-xs font-bold text-red-400 mb-1.5">Pilih Pesanan Klien (Yang Telah Selesai) *</label>
                            <select x-model="selectedPengajuan" @change="loadDataKlien()" class="w-full bg-zinc-950 border border-red-900/50 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                <option value="">-- Pilih Pesanan & Status --</option>
                                @foreach($pengajuans as $pesanan)
                                    @php
                                        $statusBayar = $pesanan->status_pengajuan === 'Lunas / Aktif' ? 'LUNAS' : 'Menunggu Pembayaran';
                                    @endphp
                                    <option value="{{ $pesanan->id }}"
                                        data-nama="{{ $pesanan->user?->nama_perusahaan ?: ($pesanan->user?->name ?? 'Klien (Data Terhapus)') }}"
                                            data-instansi="{{ $pesanan->user?->nama_perusahaan ?? 'Personal' }}">
                                        {{ $pesanan->nomor_pengajuan }} - {{ $pesanan->user?->name ?? 'Klien (Data Terhapus)' }} [Status: {{ $statusBayar }}]
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-zinc-500 mt-1">Data Pihak Kedua akan terisi otomatis.</p>
                        </div>
                        
                        <!-- Data Surat (Nomor BAST Otomatis) -->
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">Nomor Surat BAST (Otomatis) *</label>
                            <input type="text" x-model="noSurat" readonly class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-zinc-400 text-sm cursor-not-allowed">
                            <p class="text-[10px] text-zinc-500 mt-1">Format: Nomor Urut / Jenis / Bulan (Romawi) / Tahun</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 mb-1.5">Tanggal BAST *</label>
                            <input type="date" x-model="tanggalBast" @change="updateNomorSuratDanTerbilang()" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 [color-scheme:dark]">
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

                        <!-- Data Pihak Kedua (Klien) -->
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

                    <form action="{{ route('admin.bast.store') }}" method="POST" @submit="prepareSubmit($event)">
                        @csrf
                        <input type="hidden" name="pengajuan_id" x-model="selectedPengajuan">
                        <input type="hidden" name="no_surat" x-model="noSurat">
                        <input type="hidden" name="html_content" id="html_content_input">

                        <button type="submit" class="mt-6 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-lg flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan BAST ke Arsip
                        </button>
                    </form>
                </div>
            </div>

            <!-- ==========================================
                 KOLOM KANAN: LIVE PREVIEW KERTAS A4 (8 Kolom)
            =========================================== -->
            <div class="xl:col-span-8 overflow-x-auto bg-zinc-800/30 p-4 md:p-8 rounded-2xl flex justify-center items-start">
                
                <!-- KERTAS BAST -->
                <div id="kertas-bast" class="bg-white text-black w-[210mm] min-h-[297mm] shadow-2xl p-[20mm] text-[14px] leading-relaxed relative font-serif">
                    
                    <div class="text-center mb-8">
                        <h2 class="text-lg font-bold underline decoration-2 uppercase tracking-wide">BERITA ACARA SERAH TERIMA PEKERJAAN</h2>
                        <p class="mt-1 font-medium text-sm" x-text="noSurat || '.../BCC-BASTP/.../2026'"></p>
                    </div>

                    <div class="mb-6 text-justify">
                        <p>
                            Pada hari ini <em x-text="hariTerbilang"></em> tanggal <em x-text="tglTerbilang"></em> 
                            Bulan <em x-text="bulanTerbilang"></em> tahun <em x-text="tahunTerbilang"></em> 
                            (<span x-text="formatTanggalAngka(tanggalBast)"></span>), yang bertanda tangan dibawah ini :
                        </p>
                    </div>

                    <div class="mb-6 space-y-4 ml-2">
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

                    <div class="mb-4 text-justify">
                        <p><strong>PIHAK PERTAMA</strong> telah melaksanakan pekerjaan penempatan media luar ruang billboard dengan data sebagai berikut :</p>
                    </div>

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
                            <template x-if="daftarLokasi.length === 0">
                                <tr>
                                    <td class="border border-black p-2 align-middle">1</td>
                                    <td class="border border-black p-2 align-middle text-left leading-tight">...</td>
                                    <td class="border border-black p-2 align-middle">-<br><span class="text-xs">V-FL</span></td>
                                    <td class="border border-black p-2 align-middle font-semibold" x-text="naskahVisual"></td>
                                    <td class="border border-black p-2 align-middle text-xs leading-tight">...<br>s.d<br>...</td>
                                </tr>
                            </template>

                            <template x-for="(lokasi, index) in daftarLokasi" :key="index">
                                <tr>
                                    <td class="border border-black p-2 align-middle" x-text="index + 1"></td>
                                    <td class="border border-black p-2 align-middle text-left leading-tight" x-text="lokasi.lokasi"></td>
                                    <td class="border border-black p-2 align-middle">
                                        <span x-text="lokasi.ukuran"></span><br><span class="text-xs">V-FL</span>
                                    </td>
                                    <td class="border border-black p-2 align-middle font-semibold" x-text="naskahVisual"></td>
                                    <td class="border border-black p-2 align-middle text-xs leading-tight">
                                        <span x-text="formatTanggalIndo(lokasi.mulai_sewa)"></span><br>s.d<br><span x-text="formatTanggalIndo(lokasi.selesai_sewa)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div class="mb-8">
                        <p class="mb-2">Dengan hasil sebagai berikut :</p>
                        <ol class="list-decimal pl-5 space-y-1 text-justify">
                            <li><strong>PIHAK PERTAMA</strong> telah selesai melaksanakan pekerjaan penempatan media luar ruang billboard dengan baik.</li>
                            <li><strong>PIHAK PERTAMA</strong> menyerahkan hasil pekerjaan tersebut kepada <strong>PIHAK KEDUA</strong> dan <strong>PIHAK KEDUA</strong> menerima dengan baik pekerjaan tersebut dari <strong>PIHAK PERTAMA.</strong></li>
                            <li>Melampirkan dokumentasi visual.</li>
                        </ol>
                    </div>

                    <div class="mb-12 text-justify">
                        <p>Demikian Berita Acara ini dibuat dan ditanda tangani oleh kedua belah pihak pada hari, tanggal, dan tahun tersebut diatas.</p>
                    </div>

                    <div class="flex justify-between text-center mt-10 px-8">
                        <div class="w-1/2">
                            <p class="mb-20">PIHAK PERTAMA</p>
                            <p class="font-bold underline" x-text="pihak1Nama"></p>
                        </div>
                        <div class="w-1/2">
                            <p class="mb-20">PIHAK KEDUA</p>
                            <p class="font-bold underline" x-text="pihak2Nama"></p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- PRE-PROCESS DATA DI PHP -->
    @php
        // Hitung total BAST yang sudah tersimpan di database untuk menentukan nomor urut berikutnya
        $nextNumber = \App\Models\Dokumen::where('jenis_dokumen', 'LIKE', '%BAST%')->count() + 1;
        $formattedUrut = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $mappedPesanan = $pengajuans->map(function($pesanan) {
            return [
                'id' => $pesanan->id,
                'nama_klien' => $pesanan->user?->name ?? 'Klien (Data Terhapus)',
                'instansi' => $pesanan->user?->nama_perusahaan ?? 'Personal',
                'alamat' => $pesanan->user?->alamat ?? 'Alamat tidak diketahui',
                'status_bayar' => $pesanan->status_pengajuan === 'Lunas / Aktif' ? 'LUNAS' : 'Menunggu Pembayaran',
                'titik_lokasi' => $pesanan->details->map(function($detail) use ($pesanan) {
                    $info = \Illuminate\Support\Facades\DB::table('billboards')->where('kode_titik', $detail->kode_titik)->first();
                    return [
                        'lokasi' => $info ? $info->lokasi : 'Lokasi tidak ditemukan',
                        'ukuran' => $info ? $info->ukuran : '-',
                        'mulai_sewa' => $pesanan->mulai_sewa,
                        'selesai_sewa' => $pesanan->selesai_sewa
                    ];
                })->values()->toArray()
            ];
        })->values()->toArray();
    @endphp

    <!-- SCRIPT ALPINE.JS UNTUK BAST -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bastBuilder', () => ({
                selectedPengajuan: '',
                noSurat: '',
                tanggalBast: new Date().toISOString().split('T')[0],
                naskahVisual: 'Visual Default',
                
                pihak1Nama: 'Lesmono Hendra Kusumah',
                pihak1Instansi: 'CV. Boma Cipta Citra',
                
                pihak2Nama: 'Nama Klien',
                pihak2Instansi: 'Perusahaan Klien',
                pihak2Alamat: 'Alamat Klien akan muncul di sini',

                hariTerbilang: '', tglTerbilang: '', bulanTerbilang: '', tahunTerbilang: '',
                daftarLokasi: [],
                semuaPesanan: @json($mappedPesanan),
                baseUrut: '{{ $formattedUrut }}',

                init() {
                    this.updateNomorSuratDanTerbilang();
                },

                formatRomawi(bulanAngka) {
                    const romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                    return romawi[bulanAngka] || 'I';
                },

                updateNomorSuratDanTerbilang() {
                    if(!this.tanggalBast) return;
                    let d = new Date(this.tanggalBast);
                    
                    let bulanRomawi = this.formatRomawi(d.getMonth() + 1);
                    let tahun = d.getFullYear();

                    // Rakit format otomatis: 001/BCC-BASTP/VII/2026
                    this.noSurat = `${this.baseUrut}/BCC-BASTP/${bulanRomawi}/${tahun}`;

                    this.formatTanggalTerbilang();
                },

                formatTanggalIndo(tgl) {
                    if(!tgl) return '';
                    let d = new Date(tgl);
                    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
                },

                loadDataKlien() {
                    let pesanan = this.semuaPesanan.find(p => p.id == this.selectedPengajuan);
                    
                    if(pesanan) {
                        this.pihak2Nama = pesanan.nama_klien;
                        this.pihak2Instansi = pesanan.instansi + ' (' + pesanan.status_bayar + ')';
                        this.pihak2Alamat = pesanan.alamat;
                        this.daftarLokasi = pesanan.titik_lokasi;
                    } else {
                        this.pihak2Nama = 'Nama Klien';
                        this.pihak2Instansi = 'Perusahaan Klien';
                        this.pihak2Alamat = 'Alamat Klien akan muncul di sini';
                        this.daftarLokasi = [];
                    }
                },

                formatTanggalAngka(dateString) {
                    if(!dateString) return '';
                    let d = new Date(dateString);
                    return ('0' + d.getDate()).slice(-2) + ' - ' + ('0' + (d.getMonth() + 1)).slice(-2) + ' - ' + d.getFullYear();
                },

                formatTanggalTerbilang() {
                    let d = new Date(this.tanggalBast);
                    const namaHari = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
                    const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    
                    const angkaKeKata = (angka) => {
                        const kata = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
                        if(angka < 12) return kata[angka];
                        if(angka < 20) return kata[angka - 10] + ' belas';
                        if(angka < 100) return kata[Math.floor(angka / 10)] + ' puluh ' + (angka % 10 !== 0 ? kata[angka % 10] : '');
                        if(angka < 200) return 'seratus ' + angkaKeKata(angka - 100);
                        if(angka < 1000) return kata[Math.floor(angka / 100)] + ' ratus ' + (angka % 100 !== 0 ? angkaKeKata(angka % 100) : '');
                        if(angka < 2000) return 'seribu ' + angkaKeKata(angka - 1000);
                        if(angka < 10000) return kata[Math.floor(angka / 1000)] + ' ribu ' + (angka % 1000 !== 0 ? angkaKeKata(angka % 1000) : '');
                        return angka.toString(); 
                    };

                    this.hariTerbilang = namaHari[d.getDay()];
                    this.tglTerbilang = angkaKeKata(d.getDate()).trim();
                    this.bulanTerbilang = namaBulan[d.getMonth()];
                    this.tahunTerbilang = angkaKeKata(d.getFullYear()).trim();
                },

                prepareSubmit(event) {
                    if(!this.selectedPengajuan || !this.noSurat || !this.naskahVisual) {
                        event.preventDefault(); // Batalkan submit form
                        alert('Harap pilih Pesanan Klien, isi Nomor Surat BAST, dan Naskah Reklame terlebih dahulu!');
                        return;
                    }
                    
                    // Ambil isi HTML kertas dan taruh di input hidden
                    document.getElementById('html_content_input').value = document.getElementById('kertas-bast').innerHTML;
                }
            }));
        });
    </script>
</x-boma-layout>