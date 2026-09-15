<x-boma-layout>
    <!-- CSS Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">
        
        <!-- KOLOM KIRI (2/3): PETA & DAFTAR REKLAME -->
        <div class="lg:col-span-2 flex flex-col space-y-6 relative">
            
            <!-- TIRAI PENGUNCI -->
            <div id="overlay-kunci" class="absolute inset-0 bg-zinc-950/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-red-500 shadow-[0_0_30px_rgba(220,38,38,0.2)] transition-all duration-500">
                <div class="bg-zinc-900 p-6 rounded-2xl text-center max-w-sm border border-zinc-700">
                    <svg class="w-12 h-12 text-red-500 animate-bounce mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-white font-black text-lg mb-1">Tentukan Jadwal Sewa</h3>
                    <p class="text-zinc-400 text-xs">Silakan pilih Tanggal Mulai dan Selesai di panel kanan terlebih dahulu agar sistem dapat mencari titik reklame yang kosong.</p>
                </div>
            </div>

            <!-- Peta Interaktif -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 shadow-xl flex flex-col h-[450px]">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-md font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        Visualisasi Peta
                    </h2>
                    <div class="flex space-x-3 text-[10px] font-medium bg-zinc-950 p-2 rounded-lg border border-zinc-800">
                        <span class="flex items-center text-zinc-400"><div class="w-2.5 h-2.5 bg-emerald-500 rounded-full mr-1.5"></div> Kosong</span>
                        <span class="flex items-center text-zinc-400" title="Kosong, tapi sudah ada yang booking di tanggal lain"><div class="w-2.5 h-2.5 bg-emerald-500 border-[3px] border-black rounded-full mr-1.5"></div> Kosong (Bertanda)</span>
                        <span class="flex items-center text-zinc-400"><div class="w-2.5 h-2.5 bg-yellow-500 rounded-full mr-1.5"></div> Booking</span>
                        <span class="flex items-center text-zinc-400"><div class="w-2.5 h-2.5 bg-red-500 rounded-full mr-1.5"></div> Tersewa</span>
                    </div>
                </div>
                <div id="map-container" class="w-full flex-1 bg-zinc-950 rounded-xl border border-zinc-800 relative z-0 overflow-hidden">
                    <div id="map" class="absolute inset-0 z-0"></div>
                </div>
            </div>

            <!-- Tabel Daftar Reklame -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl flex flex-col max-h-[600px]">
                
                <!-- FITUR FILTER & PENCARIAN BARU -->
                <div class="p-4 border-b border-zinc-800 bg-zinc-950/50 flex flex-col md:flex-row gap-3 rounded-t-2xl">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="filter_search" placeholder="Cari nama jalan atau kode titik..." class="w-full bg-zinc-900 border border-zinc-700 text-white text-xs rounded-lg pl-9 pr-3 py-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    </div>
                    
                    <!-- FITUR KATALOG DAERAH -->
                    <div class="w-full md:w-1/3">
                        <select id="filter_daerah" class="w-full bg-zinc-900 border border-zinc-700 text-white text-xs rounded-lg px-3 py-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 cursor-pointer">
                            <option value="">Semua Daerah</option>
                            <option value="cihampelas">Cihampelas</option>
                            <option value="dago">Dago</option>
                            <option value="cimahi">Cimahi</option>
                            <option value="cibiru">Cibiru</option>
                            <option value="soekarno hatta">Soekarno Hatta</option>
                            <option value="kopo">Kopo</option>
                        </select>
                    </div>

                    <div class="w-full md:w-1/3">
                        <select id="filter_size" class="w-full bg-zinc-900 border border-zinc-700 text-white text-xs rounded-lg px-3 py-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 cursor-pointer">
                            <option value="">Semua Ukuran</option>
                            <!-- Diisi Otomatis via JS -->
                        </select>
                    </div>
                </div>

                <div class="px-4 py-2 border-b border-zinc-800 flex justify-between items-center bg-zinc-900">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Titik Tersedia</h4>
                    <span class="text-[10px] font-bold text-zinc-500" id="hitung-tabel">Pilih tanggal terlebih dahulu...</span>
                </div>
                
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left text-sm text-zinc-400">
                        <thead class="text-[10px] text-zinc-500 uppercase bg-zinc-950/90 sticky top-0 z-10 border-b border-zinc-800">
                            <tr>
                                <th class="px-4 py-2 font-black w-10 text-center">Pilih</th>
                                <th class="px-4 py-2 font-black">Detail Reklame</th>
                                <th class="px-4 py-2 font-black text-right">Harga/Bulan</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-body" class="divide-y divide-zinc-800">
                            <!-- Dirender via Javascript API -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (1/3): FORMULIR & KERANJANG -->
        <div class="lg:col-span-1 space-y-6 relative z-[60]">
            
            <!-- BANNER INFORMASI ALUR -->
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-2xl p-4 shadow-xl">
                <h4 class="text-sm font-bold text-blue-400 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Alur Pemesanan Cepat
                </h4>
                <ol class="text-[11px] text-zinc-300 list-decimal pl-4 space-y-1.5 marker:text-blue-500">
                    <li>Atur <b>Tanggal Mulai & Selesai</b> sewa.</li>
                    <li>Pilih titik yang <b>Tersedia (Hijau)</b> di tabel/peta.</li>
                    <li>Lengkapi profil, dokumen, & <b>Materi Iklan</b>.</li>
                    <li>Kirim pengajuan dan tunggu ACC Admin.</li>
                </ol>
            </div>

            <!-- Form Data Pemesan -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl relative ring-2 ring-red-500/50 animate-pulse-border" id="panel-tanggal">
                <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2">1. Data Pemesan & Jadwal</h3>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-red-400 mb-1">Mulai Sewa *</label>
                        <input type="text" id="input_mulai" placeholder="DD/MM/YYYY" class="w-full bg-zinc-950 border border-red-500 text-white rounded-lg focus:border-red-600 focus:ring-1 focus:ring-red-600 px-3 py-2 text-sm cursor-pointer shadow-[0_0_10px_rgba(220,38,38,0.2)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-red-400 mb-1">Selesai Sewa *</label>
                        <input type="text" id="input_selesai" placeholder="DD/MM/YYYY" class="w-full bg-zinc-950 border border-red-500 text-zinc-300 rounded-lg focus:border-red-600 focus:ring-1 focus:ring-red-600 px-3 py-2 text-sm cursor-pointer shadow-[0_0_10px_rgba(220,38,38,0.2)]">
                    </div>
                </div>

                <div class="mb-4 bg-red-500/10 border border-red-500/30 rounded-lg p-3 flex items-start shadow-inner">
                    <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[10px] text-zinc-300 leading-relaxed">
                        <strong class="text-red-400 font-bold">Informasi Durasi Sewa:</strong> Perhitungan durasi dihitung per bulan penuh. Apabila rentang tanggal penyewaan melebihi <strong>2 hari</strong> dari durasi 1 bulan, maka sistem akan secara otomatis membulatkan tagihan menjadi <strong>2 bulan</strong> (berlaku kelipatan).
                    </p>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-400 mb-1">Nama Lengkap (PIC) *</label>
                        <input type="text" id="input_nama" value="{{ Auth::user()->name ?? '' }}" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-400 mb-1">WhatsApp / No HP *</label>
                        <input type="text" id="input_wa" value="{{ Auth::user()->no_wa ?? '' }}" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-400 mb-1">Nama Perusahaan / Instansi</label>
                        <input type="text" id="input_perusahaan" value="{{ Auth::user()->nama_perusahaan ?? '' }}" placeholder="Opsional jika atas nama pribadi" class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg px-3 py-2 text-sm focus:border-red-600 focus:ring-1 focus:ring-red-600">
                    </div>

                    <!-- KOTAK MATERI VISUAL (Pakai Vanilla JS agar aman dengan Map) -->
                    <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800 mt-6">
                        <h3 class="text-sm font-bold text-white mb-3">Materi Visual & Desain *</h3>
                        
                        <!-- Checkbox Jasa Desain -->
                        <label class="flex items-start space-x-3 cursor-pointer group mb-4 p-3 bg-zinc-900 border border-zinc-700 rounded-lg hover:border-red-500 transition">
                            <input type="checkbox" name="jasa_desain" id="cek-jasa-desain" onchange="toggleMateriDesain()" class="mt-0.5 rounded border-zinc-600 text-red-600 focus:ring-red-500 bg-zinc-950 cursor-pointer">
                            <div>
                                <span class="block text-sm font-bold text-white">Gunakan Jasa Desain BOMA</span>
                                <span class="block text-[10px] text-zinc-400 mt-1">Centang jika Anda butuh kami buatkan desain. Biaya akan ditambahkan oleh Admin saat negosiasi.</span>
                            </div>
                        </label>

                        <!-- Form Upload -->
                        <div id="form-upload-materi" class="space-y-4 border-t border-zinc-800 pt-4 mt-2 transition-all">
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Link G-Drive/WeTransfer (Mentahan High-Res) *</label>
                                <input type="url" name="link_desain" id="input-link-desain" required placeholder="https://drive.google.com/..." class="w-full bg-zinc-900 border border-zinc-700 text-zinc-300 text-sm rounded-lg px-4 py-2 focus:border-red-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Upload Foto Preview (Wajib untuk validasi) *</label>
                                <input type="file" name="file_preview" id="input-file-preview" accept=".jpg,.jpeg,.png" required class="w-full bg-zinc-900 border border-zinc-700 text-zinc-300 text-sm rounded-lg px-4 py-2 focus:border-red-500 outline-none file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-zinc-800 file:text-zinc-300">
                                <p class="text-[9px] text-zinc-500 mt-1">Maks. 5MB. Hanya format JPG/PNG.</p>
                            </div>
                        </div>
                    </div>

                    <!-- KOTAK DOKUMEN LEGAL (Ditampilkan jika User belum punya NPWP) -->
                    @if(!Auth::user()->npwp)
                        <div class="bg-red-500/10 p-4 rounded-xl border border-red-500/30 mt-6 shadow-inner">
                            <h3 class="text-sm font-bold text-red-500 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Upload Dokumen Legal *
                            </h3>
                            <p class="text-[10px] text-zinc-300 mb-4 leading-relaxed">
                                Karena ini pengajuan pertama Anda, mohon unggah KTP dan NPWP untuk keperluan penerbitan Invoice & Faktur Pajak. (Hanya perlu sekali seumur hidup).
                                <br><br>
                                <span class="italic text-yellow-400">*Jika tidak memiliki NPWP, silakan upload KTP Anda kembali di kolom NPWP.</span>
                            </p>
                            
                            <div class="space-y-4 border-t border-red-500/30 pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-zinc-300 mb-1.5">Foto / Scan KTP (PDF/JPG) *</label>
                                    <input type="file" id="input_ktp" accept=".jpg,.jpeg,.png,.pdf" required class="w-full bg-zinc-950 border border-red-500/50 text-zinc-400 text-sm rounded-lg px-4 py-2 focus:border-red-500 outline-none file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-zinc-300 mb-1.5">Foto / Scan NPWP (PDF/JPG) *</label>
                                    <input type="file" id="input_npwp" accept=".jpg,.jpeg,.png,.pdf" required class="w-full bg-zinc-950 border border-red-500/50 text-zinc-400 text-sm rounded-lg px-4 py-2 focus:border-red-500 outline-none file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-700">
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            <!-- Keranjang Estimasi -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl sticky top-6 mt-6">
                <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2 flex justify-between items-center">
                    2. Keranjang & Kalkulasi
                    <span id="badge-titik" class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full font-black">0</span>
                </h3>
                
                <div id="keranjang-kosong" class="flex flex-col items-center justify-center text-center border-2 border-dashed border-zinc-800 rounded-xl p-4 mb-4">
                    <p class="text-xs text-zinc-500">Peta akan terbuka setelah tanggal dipilih.</p>
                </div>
                <div id="keranjang-isi" class="hidden mb-4 space-y-2 max-h-[150px] overflow-y-auto custom-scrollbar pr-1"></div>

                <div class="space-y-2 pt-3 border-t border-zinc-800">
                    <div class="flex justify-between text-sm text-zinc-400"><span>Total Lokasi</span><span id="calc-lokasi" class="text-white font-medium">0 Titik</span></div>
                    <div class="flex justify-between text-sm text-zinc-400"><span>Estimasi Durasi</span><span id="calc-durasi" class="text-white font-medium">0 Bulan</span></div>
                    
                    <div class="flex justify-between text-sm text-zinc-400 pt-2 mt-2 border-t border-zinc-800 border-dashed">
                        <span>Subtotal Sewa</span><span id="calc-subtotal" class="text-white font-medium">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-zinc-400">
                        <span>Pajak (PPN 11%)</span><span id="calc-ppn" class="text-emerald-400 font-medium">+ Rp 0</span>
                    </div>

                    <div class="flex justify-between items-center bg-zinc-950 p-3 rounded-xl border border-zinc-800 mt-2">
                        <span class="text-sm font-bold text-zinc-300">TOTAL ESTIMASI</span>
                        <span id="calc-grandtotal" class="text-lg font-black text-red-500">Rp 0</span>
                    </div>
                    <p class="text-[9px] text-zinc-500 mt-1 italic">*Total bersifat estimasi. Harga final beserta biaya desain (jika ada) akan ditentukan Admin.</p>
                </div>

                <button type="button" id="btn-submit" disabled class="mt-5 w-full py-3 bg-zinc-700 text-zinc-400 rounded-xl font-bold text-sm tracking-wide transition cursor-not-allowed">
                    Kirim Pengajuan Sewa
                </button>
            </div>
        </div>
    </div>

    <!-- Script Flatpickr & Leaflet -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Inisialisasi Peta Leaflet
            var map = L.map('map').setView([-6.914744, 107.609810], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            var markerLayerGroup = L.layerGroup().addTo(map);
            
            const inputMulai = document.getElementById('input_mulai');
            const inputSelesai = document.getElementById('input_selesai');
            const rp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            
            let globalTitikData = [];
            let titikTerpilih = {};
            let globalTotalEstimasiAkhir = 0;
            let durasiBulanGlobal = 0;

            const userHasNpwp = "{{ Auth::user()->npwp ? 'yes' : 'no' }}";

            // 2. Setup Flatpickr (Dibatasi minimal hari ini)
            let fpSelesai = flatpickr(inputSelesai, { 
                dateFormat: "d/m/Y",
                minDate: "today",
                onChange: cekKetersediaanKeServer 
            });
            
            flatpickr(inputMulai, {
                dateFormat: "d/m/Y",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        let tglMulai = selectedDates[0];
                        let tglSelesai = new Date(tglMulai);
                        tglSelesai.setMonth(tglSelesai.getMonth() + 1);
                        
                        // Update minDate untuk selesai_sewa agar tidak bisa mundur dari mulai_sewa
                        fpSelesai.set('minDate', tglMulai); 
                        fpSelesai.setDate(tglSelesai);
                        
                        cekKetersediaanKeServer(); 
                    }
                }
            });

            // 3. EVENT LISTENER FILTER PENCARIAN & UKURAN
            document.getElementById('filter_search').addEventListener('input', terapkanFilter);
            document.getElementById('filter_size').addEventListener('change', terapkanFilter);
            document.getElementById('filter_daerah').addEventListener('change', terapkanFilter); // Event listener baru

            // 4. FUNGSI CEK KETERSEDIAAN
            async function cekKetersediaanKeServer() {
                if(!inputMulai.value || !inputSelesai.value) return;

                document.getElementById('hitung-tabel').innerText = "Sedang mensinkronkan...";
                document.getElementById('tabel-body').innerHTML = `<tr><td colspan="3" class="text-center py-6"><svg class="animate-spin h-6 w-6 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></td></tr>`;
                
                try {
                    let response = await fetch('{{ route("klien.cek-ketersediaan") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ mulai: inputMulai.value, selesai: inputSelesai.value })
                    });
                    
                    let data = await response.json();
                    globalTitikData = data;
                    titikTerpilih = {}; 
                    
                    isiDropdownUkuran(data);
                    terapkanFilter();
                    hitungKeranjang();
                    
                    document.getElementById('overlay-kunci').classList.add('opacity-0', 'pointer-events-none');
                    document.getElementById('panel-tanggal').classList.remove('ring-2', 'ring-red-500/50', 'animate-pulse-border');
                } catch (error) {
                    alert('Gagal mengambil ketersediaan data. Cek koneksi Anda.');
                }
            }

            // 5. LOGIKA FILTER (Diperbarui dengan Filter Daerah)
            function isiDropdownUkuran(data) {
                let ukuranUnik = [...new Set(data.map(item => item.ukuran))].filter(Boolean);
                let selectSize = document.getElementById('filter_size');
                let prevVal = selectSize.value;
                
                selectSize.innerHTML = '<option value="">Semua Ukuran</option>' + 
                    ukuranUnik.map(s => `<option value="${s}">${s}</option>`).join('');
                selectSize.value = prevVal; 
            }

            function terapkanFilter() {
                let kataKunci = document.getElementById('filter_search').value.toLowerCase();
                let ukuran = document.getElementById('filter_size').value;
                let daerah = document.getElementById('filter_daerah').value.toLowerCase(); // Ambil nilai daerah
                
                let dataTersaring = globalTitikData.filter(item => {
                    let lokasiText = item.lokasi.toLowerCase();
                    let kodeText = item.kode_titik.toLowerCase();
                    
                    let cocokKata = lokasiText.includes(kataKunci) || kodeText.includes(kataKunci);
                    let cocokUkuran = (ukuran === "") || (item.ukuran === ukuran);
                    let cocokDaerah = (daerah === "") || lokasiText.includes(daerah); // Filter berdasarkan daerah
                    
                    return cocokKata && cocokUkuran && cocokDaerah;
                });

                dataTersaring.sort((a, b) => {
                    const bobotStatus = (status) => {
                        if (status.includes('Tersedia')) return 1; 
                        if (status === 'Booking') return 2;        
                        if (status === 'Tersewa') return 3;        
                        return 4;
                    };
                    return bobotStatus(a.status) - bobotStatus(b.status);
                });
                
                renderPetaDanTabel(dataTersaring);
            }

            // 6. RENDER PETA DAN TABEL
            function renderPetaDanTabel(data) {
                markerLayerGroup.clearLayers(); 
                let htmlTabel = '';
                
                // Menentukan Bounds Peta jika ada data
                let markerBounds = [];

                data.forEach(item => {
                    let warnaFill = '#22c55e'; 
                    let warnaBorder = '#ffffff';
                    let weightBorder = 2;
                    let isDisabled = false;
                    let badgeTabel = '';

                    if (item.status === 'Booking') {
                        warnaFill = '#eab308'; isDisabled = true;
                        badgeTabel = '<span class="bg-yellow-500/10 text-yellow-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-yellow-500/20">BOOKING</span>';
                    } else if (item.status === 'Tersewa') {
                        warnaFill = '#ef4444'; isDisabled = true;
                        badgeTabel = '<span class="bg-red-500/10 text-red-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-red-500/20">TERSEWA</span>';
                    } else if (item.status === 'Tersedia Bertanda') {
                        warnaFill = '#22c55e'; warnaBorder = '#000000'; weightBorder = 4;
                        badgeTabel = '<span class="bg-emerald-500/10 text-emerald-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-emerald-500/20 shadow-[0_0_0_1px_#000]">TERSEDIA*</span>';
                    } else {
                        badgeTabel = '<span class="bg-emerald-500/10 text-emerald-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-emerald-500/20">KOSONG</span>';
                    }

                    var marker = L.circleMarker([parseFloat(item.latitude), parseFloat(item.longitude)], {
                        radius: 8, fillColor: warnaFill, color: warnaBorder, weight: weightBorder, fillOpacity: 1
                    });
                    
                    markerBounds.push([parseFloat(item.latitude), parseFloat(item.longitude)]);

                    let isChecked = titikTerpilih[item.kode_titik] ? true : false;
                    if(isChecked) {
                        marker.setStyle({ fillColor: '#3b82f6', color: '#60a5fa', weight: 4 });
                    }

                    let btnText = isChecked ? 'Batal Pilih' : 'Pilih Titik';
                    let btnClass = isChecked ? 'bg-zinc-600 hover:bg-zinc-700' : 'bg-red-600 hover:bg-red-700';
                    
                    let btnPetaHtml = isDisabled ? 
                        `<div class="mt-2 w-full bg-zinc-700 text-zinc-300 text-[10px] font-bold py-1.5 rounded text-center">Tidak Tersedia</div>` : 
                        `<button id="btn-map-${item.kode_titik}" onclick="toggleDariPeta('${item.kode_titik}')" class="mt-2 w-full ${btnClass} text-white text-[10px] font-bold py-1.5 rounded transition">${btnText}</button>`;

                    let infoHtml = item.info ? `<div class="text-[9px] text-zinc-800 bg-yellow-300 px-2 py-1.5 rounded mb-2 font-medium leading-relaxed">${item.info}</div>` : '';

                    marker.bindPopup(`
                        <div class="min-w-[150px]">
                            <h4 class="font-black text-red-600 text-xs">${item.kode_titik}</h4>
                            <p class="text-[10px] text-zinc-600 leading-tight mb-2 border-b border-zinc-200 pb-1">${item.lokasi}</p>
                            ${infoHtml}
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[9px] text-zinc-500">Harga/Bulan:</span>
                                <span class="text-[11px] font-black text-zinc-900">${rp(item.harga)}</span>
                            </div>
                            ${btnPetaHtml}
                        </div>
                    `);
                    
                    markerLayerGroup.addLayer(marker);

                    window.markerData = window.markerData || {};
                    window.markerData[item.kode_titik] = { obj: marker, baseFill: warnaFill, baseBorder: warnaBorder, baseWeight: weightBorder };

                    htmlTabel += `
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-4 py-2 text-center align-middle">
                                <input type="checkbox" onchange="centangTabel(this)"
                                    class="checkbox-titik w-4 h-4 text-red-600 bg-zinc-950 border-zinc-600 rounded focus:ring-red-600 cursor-pointer" 
                                    data-kode="${item.kode_titik}" data-harga="${item.harga}" data-nama="${item.lokasi}"
                                    ${isDisabled ? 'disabled' : ''} ${isChecked ? 'checked' : ''}>
                            </td>
                            <td class="px-4 py-2 cursor-default">
                                <div class="font-bold text-white mb-0.5 text-xs">${item.kode_titik}</div>
                                <div class="text-[10px] text-zinc-400 mb-1 leading-relaxed line-clamp-1" title="${item.lokasi}">${item.lokasi}</div>
                                <div class="flex items-center space-x-1.5 mt-1">
                                    <span class="bg-zinc-800 text-zinc-300 text-[9px] px-1.5 py-0.5 rounded font-bold">${item.ukuran}</span>
                                    ${badgeTabel}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-right font-medium text-white align-middle text-xs">${rp(item.harga)}</td>
                        </tr>
                    `;
                });

                document.getElementById('tabel-body').innerHTML = htmlTabel;
                document.getElementById('hitung-tabel').innerText = `Menampilkan ${data.length} Titik`;
                
                // Menyesuaikan tampilan peta ke marker yang ada jika ada filter daerah
                let daerah = document.getElementById('filter_daerah').value;
                if (daerah !== "" && markerBounds.length > 0) {
                     map.fitBounds(markerBounds, { padding: [20, 20] });
                } else if (daerah === "") {
                     map.setView([-6.914744, 107.609810], 12); // Reset view ke default jika "Semua Daerah"
                }

                setTimeout(function(){ map.invalidateSize(); }, 500);
            }

            // 7. TOGGLE CHECKBOX
            window.toggleDariPeta = function(kode) {
                let cb = document.querySelector(`.checkbox-titik[data-kode="${kode}"]`);
                if(cb && !cb.disabled) {
                    cb.checked = !cb.checked;
                    centangTabel(cb); 
                } else if (!cb) {
                    let d = globalTitikData.find(x => x.kode_titik === kode);
                    if(d) {
                        if(titikTerpilih[kode]) delete titikTerpilih[kode];
                        else titikTerpilih[kode] = { harga: parseFloat(d.harga), nama: d.lokasi };
                        centangTabel({ dataset: { kode: kode, harga: d.harga, nama: d.lokasi }, checked: !!titikTerpilih[kode] }, true);
                    }
                }
            }

            window.centangTabel = function(cb, dariPetaHidden = false) {
                let kode = cb.dataset.kode;
                let dataMarker = window.markerData[kode];

                if (cb.checked) {
                    titikTerpilih[kode] = { harga: parseFloat(cb.dataset.harga), nama: cb.dataset.nama };
                    if (dataMarker) dataMarker.obj.setStyle({ fillColor: '#3b82f6', color: '#60a5fa', weight: 4 }); 
                } else {
                    delete titikTerpilih[kode];
                    if (dataMarker) dataMarker.obj.setStyle({ fillColor: dataMarker.baseFill, color: dataMarker.baseBorder, weight: dataMarker.baseWeight }); 
                }
                
                let btn = document.getElementById(`btn-map-${kode}`);
                if(btn) {
                    btn.innerText = cb.checked ? "Batal Pilih" : "Pilih Titik";
                    btn.className = cb.checked ? "mt-2 w-full bg-zinc-600 hover:bg-zinc-700 text-white text-[10px] font-bold py-1.5 rounded transition" : "mt-2 w-full bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold py-1.5 rounded transition";
                }

                hitungKeranjang();
            }

            // 8. HITUNG KERANJANG
            function hitungKeranjang() {
                let [d1_d, d1_m, d1_y] = inputMulai.value.split('/');
                let [d2_d, d2_m, d2_y] = inputSelesai.value.split('/');
                let d1 = new Date(d1_y, d1_m - 1, d1_d);
                let d2 = new Date(d2_y, d2_m - 1, d2_d);
                
                durasiBulanGlobal = (d2.getFullYear() - d1.getFullYear()) * 12 + (d2.getMonth() - d1.getMonth());
                if (d2.getDate() > d1.getDate()) durasiBulanGlobal++;
                if (durasiBulanGlobal <= 0) durasiBulanGlobal = 1;

                let totalItem = 0, subtotalPerBulan = 0;
                let htmlKeranjang = '';
                
                for (let kode in titikTerpilih) {
                    totalItem++; subtotalPerBulan += titikTerpilih[kode].harga;
                    htmlKeranjang += `
                        <div class="bg-zinc-950 border border-zinc-800 rounded p-2 flex justify-between items-center">
                            <div><p class="text-xs font-bold text-white">${kode}</p></div>
                            <p class="text-xs font-medium text-red-400">${rp(titikTerpilih[kode].harga)}/bln</p>
                        </div>`;
                }

                if (totalItem > 0) {
                    document.getElementById('keranjang-kosong').classList.add('hidden');
                    document.getElementById('keranjang-isi').classList.remove('hidden');
                    document.getElementById('keranjang-isi').innerHTML = htmlKeranjang;
                    
                    document.getElementById('btn-submit').disabled = false;
                    document.getElementById('btn-submit').classList.replace('bg-zinc-700', 'bg-red-600');
                    document.getElementById('btn-submit').classList.replace('text-zinc-400', 'text-white');
                    document.getElementById('btn-submit').classList.remove('cursor-not-allowed');
                    document.getElementById('btn-submit').classList.add('hover:bg-red-700', 'shadow-[0_0_15px_rgba(220,38,38,0.3)]');
                } else {
                    document.getElementById('keranjang-kosong').classList.remove('hidden');
                    document.getElementById('keranjang-isi').classList.add('hidden');
                    
                    document.getElementById('btn-submit').disabled = true;
                    document.getElementById('btn-submit').className = "mt-5 w-full py-3 bg-zinc-700 text-zinc-400 rounded-xl font-bold text-sm tracking-wide transition cursor-not-allowed";
                }

                let subtotal = subtotalPerBulan * durasiBulanGlobal;
                let ppn = subtotal * 0.11;
                let grandTotal = subtotal + ppn;
                
                globalTotalEstimasiAkhir = grandTotal;

                document.getElementById('badge-titik').innerText = totalItem;
                document.getElementById('calc-lokasi').innerText = totalItem + " Titik";
                document.getElementById('calc-durasi').innerText = durasiBulanGlobal + " Bulan";
                
                if(document.getElementById('calc-subtotal')) document.getElementById('calc-subtotal').innerText = rp(subtotal);
                if(document.getElementById('calc-ppn')) document.getElementById('calc-ppn').innerText = '+ ' + rp(ppn);
                
                document.getElementById('calc-grandtotal').innerText = rp(grandTotal);
            }

            // 9. AKSI SUBMIT (FORMDATA) - MENGGUNAKAN SWEETALERT2
            document.getElementById('btn-submit').addEventListener('click', async function() {
                
                let inputNama = document.getElementById('input_nama').value;
                let inputWa = document.getElementById('input_wa').value;
                if(!inputNama || !inputWa) {
                    Swal.fire({ title: 'Data Belum Lengkap!', text: 'Mohon isi Nama Lengkap dan WhatsApp Anda!', icon: 'warning', confirmButtonColor: '#dc2626', background: '#18181b', color: '#f4f4f5', customClass: { popup: 'border border-zinc-800 rounded-2xl' }}); 
                    return;
                }

                // ID DISINKRONKAN DENGAN HTML
                let isJasaDesain = document.getElementById('cek-jasa-desain').checked;
                let linkMateri = document.getElementById('input-link-desain').value;
                let filePreview = document.getElementById('input-file-preview').files[0];

                if (!isJasaDesain && !linkMateri) {
                    Swal.fire({ title: 'Materi Belum Lengkap!', text: 'Mohon masukkan Link G-Drive/WeTransfer materi mentah Anda, atau centang "Gunakan Jasa Desain BOMA".', icon: 'warning', confirmButtonColor: '#dc2626', background: '#18181b', color: '#f4f4f5', customClass: { popup: 'border border-zinc-800 rounded-2xl' }}); 
                    return;
                }
                
                if (!isJasaDesain && !filePreview) {
                    Swal.fire({ title: 'Preview Kosong!', text: 'Mohon unggah Foto Preview (JPG/PNG) materi desain Anda untuk validasi konten oleh Admin.', icon: 'warning', confirmButtonColor: '#dc2626', background: '#18181b', color: '#f4f4f5', customClass: { popup: 'border border-zinc-800 rounded-2xl' }}); 
                    return;
                }

                let fileKtp = document.getElementById('input_ktp') ? document.getElementById('input_ktp').files[0] : null;
                let fileNpwp = document.getElementById('input_npwp') ? document.getElementById('input_npwp').files[0] : null;
                
                if (userHasNpwp === 'no' && (!fileKtp || !fileNpwp)) {
                    Swal.fire({ title: 'Dokumen Wajib!', text: 'Silakan unggah dokumen KTP dan NPWP Anda terlebih dahulu untuk memproses pesanan.', icon: 'warning', confirmButtonColor: '#dc2626', background: '#18181b', color: '#f4f4f5', customClass: { popup: 'border border-zinc-800 rounded-2xl' }}); 
                    return;
                }

                let btnSubmit = this;
                btnSubmit.innerText = "Mengirim..."; btnSubmit.disabled = true;

                let formData = new FormData();
                formData.append('mulai_sewa', inputMulai.value);
                formData.append('selesai_sewa', inputSelesai.value);
                formData.append('estimasi_harga', Math.round(globalTotalEstimasiAkhir));
                
                formData.append('nama', inputNama);
                formData.append('no_wa', inputWa);
                formData.append('nama_perusahaan', document.getElementById('input_perusahaan').value);
                
                formData.append('jasa_desain', isJasaDesain ? '1' : '0');
                if (!isJasaDesain) formData.append('link_desain', linkMateri);
                if (filePreview) formData.append('file_preview', filePreview);
                
                Object.keys(titikTerpilih).forEach(kode => {
                    formData.append('titik_reklame[]', kode);
                });

                if(fileKtp) formData.append('file_ktp', fileKtp);
                if(fileNpwp) formData.append('file_npwp', fileNpwp);

                try {
                    let response = await fetch('{{ route("klien.pengajuan.store") }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });
                    
                    let result = await response.json();
                    
                    if (result.success) {
                        // TAMPILAN SWEETALERT SUCCESS
                        Swal.fire({
                            title: 'Pengajuan Berhasil Dikirim! 🎉',
                            text: 'Pesanan beserta lampiran preview materi telah masuk ke Admin untuk divalidasi. Titik reklame otomatis diamankan (Tahap Booking).',
                            icon: 'success',
                            confirmButtonColor: '#dc2626', // Warna Merah BOMA
                            background: '#18181b', // Background dark mode (zinc-900)
                            color: '#f4f4f5', // Text putih
                            customClass: {
                                popup: 'border border-zinc-800 rounded-2xl shadow-2xl'
                            }
                        }).then((result) => {
                            window.location.reload(); 
                        });
                    } else {
                        // TAMPILAN SWEETALERT ERROR DARI SERVER
                        Swal.fire({ title: 'Gagal!', text: result.message, icon: 'error', confirmButtonColor: '#dc2626', background: '#18181b', color: '#f4f4f5', customClass: { popup: 'border border-zinc-800 rounded-2xl' }});
                    }
                } catch (error) {
                    // TAMPILAN SWEETALERT ERROR KONEKSI
                    Swal.fire({ title: 'Terjadi Kesalahan', text: 'Gagal memproses pengajuan. Pastikan file preview kurang dari 5MB dan koneksi internet Anda stabil.', icon: 'error', confirmButtonColor: '#dc2626', background: '#18181b', color: '#f4f4f5', customClass: { popup: 'border border-zinc-800 rounded-2xl' }});
                } finally {
                    btnSubmit.innerText = "Kirim Pengajuan Sewa"; btnSubmit.disabled = false;
                }
            });
        });

        // 10. Fungsi Toggle Terpisah di Luar DOMContentLoaded agar bisa diakses oleh HTML (onchange)
        function toggleMateriDesain() {
            let isChecked = document.getElementById('cek-jasa-desain').checked;
            let divUpload = document.getElementById('form-upload-materi');
            let inputLink = document.getElementById('input-link-desain');
            let inputFile = document.getElementById('input-file-preview');

            if (isChecked) {
                divUpload.style.display = 'none';
                inputLink.required = false;
                inputFile.required = false;
            } else {
                divUpload.style.display = 'block';
                inputLink.required = true;
                inputFile.required = true;
            }
        }
    </script>
</x-boma-layout>