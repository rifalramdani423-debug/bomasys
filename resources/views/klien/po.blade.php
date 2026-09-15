<x-boma-layout>
    <div class="pb-10" x-data="poBuilder()">
        
        <div class="mb-6">
            <h1 class="text-2xl font-black text-white mb-1">Penerbitan Purchase Order (PO) 📝</h1>
            <p class="text-sm text-zinc-400">Pilih nomor pesanan Anda, lengkapi data perusahaan, dan kirimkan dokumen PO resmi ke Admin.</p>
        </div>

        <!-- DROPDOWN PILIHAN PESANAN -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 mb-6 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-white">Pilih Pesanan untuk Dokumen PO</h3>
                <p class="text-xs text-zinc-400">Silakan pilih nomor pesanan aktif Anda untuk memuat rincian titik dan termin pembayaran.</p>
            </div>
            
            <form method="GET" action="{{ route('klien.po') }}" class="flex items-center space-x-3 w-full md:w-auto">
                <select name="pesanan_id" onchange="this.form.submit()" class="bg-zinc-950 border border-zinc-800 text-white text-xs rounded-lg px-3 py-2 focus:border-red-600 focus:ring-1 focus:ring-red-600 w-full md:w-72 cursor-pointer">
                    @forelse($listPesanan as $item)
                        <option value="{{ $item->id }}" {{ isset($pengajuan) && $pengajuan->id == $item->id ? 'selected' : '' }}>
                            Pesanan {{ $item->nomor_pengajuan }} - Rp {{ number_format($item->harga_final ?? $item->estimasi_harga, 0, ',', '.') }} ({{ $item->status_pengajuan }})
                        </option>
                    @empty
                        <option value="">Tidak ada pesanan aktif</option>
                    @endforelse
                </select>
            </form>
        </div>

        @php
            // PERBAIKAN LOGIKA BLOKIR PO: Hanya izinkan jika statusnya "Lunas / Aktif"
            $isLocked = true;
            if ($pengajuan && $pengajuan->status_pengajuan === 'Lunas / Aktif') {
                $isLocked = false;
            }

            $grandTotal = $pengajuan->harga_final ?? $pengajuan->estimasi_harga ?? 0;
            // Decode data rincian harga (JSON) yang diinput Admin
            $rincianHarga = !empty($pengajuan->catatan_harga) ? json_decode($pengajuan->catatan_harga, true) : [];
        @endphp

        @if($isLocked)
            <!-- BANNER BLOKIR -->
            <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-8 text-center shadow-xl space-y-4">
                <div class="w-16 h-16 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white mb-1">Akses Pembuatan PO Dikunci</h3>
                    <p class="text-xs text-zinc-400 max-w-md mx-auto leading-relaxed">
                        Pesanan <span class="text-white font-bold">{{ $pengajuan->nomor_pengajuan ?? 'Pilih' }}</span> saat ini berstatus <span class="text-yellow-500 font-bold">"{{ $pengajuan->status_pengajuan ?? 'Belum Dipilih' }}"</span>. 
                        Dokumen Purchase Order (PO) baru dapat diisi dan dicetak <b>setelah Tanda Jadi / DP (Termin 1) divalidasi lunas oleh Keuangan</b>.
                    </p>
                </div>
                <div>
                    <a href="{{ route('klien.keuangan') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-lg">
                        Periksa Status Pembayaran & Upload DP
                    </a>
                </div>
            </div>
        @else
            <!-- FORMULIR & PREVIEW PO -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                <!-- KOLOM KIRI: FORM INPUT -->
                <div class="xl:col-span-4 space-y-6">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl sticky top-6">
                        <h3 class="text-md font-bold text-white mb-4 border-b border-zinc-800 pb-2">Formulir Data PO</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Nomor PO (Sistem Otomatis) *</label>
                                <input type="text" x-model="poNumber" readonly class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-400 font-mono text-sm cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Tanggal Terbit *</label>
                                <input type="date" x-model="poDate" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500 [color-scheme:dark]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Nama Perusahaan *</label>
                                <input type="text" x-model="clientName" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Alamat Lengkap Perusahaan *</label>
                                <textarea x-model="clientAddress" rows="2" placeholder="Contoh: Jl. Sudirman No. 123..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-zinc-400 mb-1.5">No. Telp / WA</label>
                                    <input type="text" x-model="clientPhone" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-zinc-400 mb-1.5">Email</label>
                                    <input type="email" x-model="clientEmail" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Nomor NPWP</label>
                                <input type="text" x-model="clientNpwp" @input="clientNpwp = formatNpwp($event.target.value)" placeholder="Contoh: 01.234.567.8-901.000" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-zinc-400 mb-1.5">Catatan Tambahan (Opsional)</label>
                                <textarea x-model="customNotes" rows="2" placeholder="Contoh: Lampiran materi visual dikirim H-3..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-red-500"></textarea>
                            </div>
                        </div>

                        <!-- TOMBOL SIMPAN -->
                        <button @click="simpanDanKirimPO" :disabled="isLoading" class="mt-6 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-red-600/20 flex justify-center items-center cursor-pointer disabled:opacity-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            <span x-text="isLoading ? 'Menyimpan & Mengirim...' : 'Simpan & Kirim ke Admin'"></span>
                        </button>
                    </div>
                </div>

                <!-- KOLOM KANAN: PREVIEW KERTAS -->
                <div class="xl:col-span-8 overflow-x-auto bg-zinc-800/30 p-4 md:p-8 rounded-2xl flex justify-center items-start">
                    
                    <div id="kertas-po" class="bg-white text-black w-[210mm] min-h-[297mm] shadow-2xl p-[15mm] text-sm relative font-sans">
                        
                        <!-- HEADER KERTAS -->
                        <div class="flex justify-between items-start mb-6 border-b-2 border-blue-900 pb-4">
                            <div class="w-1/2">
                                <h2 class="text-2xl font-black text-blue-900 tracking-wider uppercase" x-text="clientName || 'NAMA PERUSAHAAN ANDA'"></h2>
                                <p class="mt-2 text-xs leading-tight whitespace-pre-wrap" x-text="clientAddress || 'Alamat perusahaan...'"></p>
                                
                                <div class="mt-2 text-[11px] text-gray-700 space-y-0.5">
                                    <template x-if="clientPhone"><p><span class="font-bold inline-block w-16">Telp/WA</span>: <span x-text="clientPhone"></span></p></template>
                                    <template x-if="clientEmail"><p><span class="font-bold inline-block w-16">Email</span>: <span x-text="clientEmail"></span></p></template>
                                    <template x-if="clientNpwp"><p><span class="font-bold inline-block w-16">NPWP</span>: <span x-text="clientNpwp"></span></p></template>
                                </div>
                            </div>
                            <div class="text-right w-1/2">
                                <h1 class="text-3xl font-light text-blue-500 mb-4">Purchase<br><span class="font-bold">Order</span></h1>
                                <table class="ml-auto text-xs border-collapse">
                                    <tr>
                                        <td class="border border-black px-2 py-1 font-bold bg-gray-100">DATE</td>
                                        <td class="border border-black px-2 py-1 min-w-[120px]" x-text="formatTanggal(poDate)"></td>
                                    </tr>
                                    <tr>
                                        <td class="border border-black px-2 py-1 font-bold bg-gray-100">PURCHASE #</td>
                                        <td class="border border-black px-2 py-1 font-mono uppercase" x-text="poNumber || '...' "></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- VENDOR INFO -->
                        <div class="mb-4">
                            <div class="bg-blue-900 text-white font-bold px-2 py-0.5 w-1/2 text-xs">FOR (VENDOR) :</div>
                            <div class="mt-1 font-bold text-sm">CV. BOMA CIPTA CITRA</div>
                            <div class="text-xs">Jl. Wastukencana No. 99A Bandung<br>Email: marketing@bomaadvertising.com</div>
                        </div>

                        <!-- TABEL RINCIAN ITEM -->
                        <table class="w-full text-xs border-collapse border border-black mb-4">
                            <thead class="bg-blue-900 text-white text-center">
                                <tr>
                                    <th class="border border-black px-2 py-2 w-1/2">DESCRIPTION</th>
                                    <th class="border border-black px-2 py-2">UNIT PRICE (Rp)</th>
                                    <th class="border border-black px-2 py-2">QTY</th>
                                    <th class="border border-black px-2 py-2">TAXED</th>
                                    <th class="border border-black px-2 py-2">AMOUNT (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($rincianHarga))
                                    <!-- Menampilkan rincian yang diinput Admin (Sewa, Pajak, Diskon, Desain, dll) -->
                                    @foreach($rincianHarga as $item)
                                        <tr>
                                            <td class="border border-black px-3 py-3 align-top">
                                                <div class="font-bold mb-1">{{ $item['nama_item'] }}</div>
                                                
                                                <!-- Jika itu baris Sewa Titik, tambahkan info periode tanggal -->
                                                @if(str_contains(strtolower($item['nama_item']), 'sewa reklame'))
                                                    <div class="text-[10px] text-gray-600 mt-1 leading-tight">
                                                        - Periode Tayang: {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="border border-black px-2 py-3 align-top text-right">{{ number_format($item['harga_item'], 0, ',', '.') }}</td>
                                            <td class="border border-black px-2 py-3 align-top text-center">1</td>
                                            <td class="border border-black px-2 py-3 align-top text-center">-</td>
                                            <td class="border border-black px-2 py-3 align-top text-right">{{ number_format($item['harga_item'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <!-- Fallback jika data lama belum punya catatan harga JSON -->
                                    <tr>
                                        <td colspan="5" class="border border-black px-3 py-6 text-center text-gray-500">
                                            Rincian Harga (Kesepakatan) Tidak Ditemukan.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- SUMMARY & COMMENTS -->
                        <div class="flex justify-between items-start mb-6">
                            
                            <!-- Skema Pembayaran -->
                            <div class="w-1/2 pr-4">
                                <table class="w-full text-xs border-collapse border border-black">
                                    <thead class="bg-blue-900 text-white text-center">
                                        <tr><th class="px-2 py-1">OTHER COMMENTS / TERMS</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-black px-2 py-2 min-h-[60px] align-top text-[11px] leading-relaxed">
                                                <div class="font-bold mb-1 text-blue-900">Skema Pembayaran / Termin:</div>
                                                @if($pengajuan && $pengajuan->termins->count() > 0)
                                                    <ul class="list-disc pl-4 space-y-0.5 mb-2">
                                                        @foreach($pengajuan->termins as $termin)
                                                            <li>Termin {{ $termin->termin_ke }} ({{ $termin->persentase }}%): Rp {{ number_format($termin->nominal, 0, ',', '.') }} - Tempo: {{ $termin->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d M Y') : 'Menunggu kesepakatan' }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p>Sesuai kesepakatan tertulis dengan Tim Keuangan.</p>
                                                @endif

                                                <template x-if="customNotes">
                                                    <div class="mt-2 pt-2 border-t border-gray-300">
                                                        <span class="font-bold text-blue-900">Catatan Tambahan:</span>
                                                        <p class="whitespace-pre-wrap mt-0.5" x-text="customNotes"></p>
                                                    </div>
                                                </template>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total (Hanya 1 baris karena pajak sudah masuk di tabel) -->
                            <div class="w-1/2">
                                <table class="w-full text-xs border-collapse ml-auto">
                                    <tr>
                                        <td class="px-2 py-3 font-black text-right text-sm w-1/2 border-t-2 border-black">TOTAL KESELURUHAN (Nett)</td>
                                        <td class="px-2 py-3 font-black text-right text-sm bg-gray-200 border-t-2 border-black">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="mb-8 border border-gray-300 p-3 rounded text-[10px] text-gray-700 bg-gray-50">
                            <p class="font-bold text-blue-900 mb-1">Catatan Penting (Notes):</p>
                            <ol class="list-decimal pl-4 space-y-0.5">
                                <li>Pembayaran transfer wajib melalui Rekening Resmi CV. BOMA CIPTA CITRA.</li>
                                <li>Harap mencantumkan nomor PO ini pada berita acara atau keterangan transfer bank Anda.</li>
                                <li>Materi visual iklan / cetak billboard dikirimkan paling lambat H-3 sebelum masa tayang dimulai.</li>
                            </ol>
                        </div>

                        <div class="mt-8 text-right text-xs pr-8">
                            <p class="mb-14">Yours Faithfully,</p>
                            <p class="font-bold border-t border-black inline-block pt-1 uppercase" x-text="clientName || 'NAMA PERUSAHAAN'"></p>
                            <p>Authorized Signature</p>
                        </div>

                        <div class="absolute bottom-[10mm] left-0 w-full text-center text-xs text-gray-500">
                            <p>If you have any questions about this purchase, please contact us.</p>
                            <p class="font-bold">Thank You For Your Business</p>
                        </div>

                    </div>
                </div>

            </div>
        @endif
    </div>

    <!-- SCRIPT ALPINE.JS & AJAX SIMPAN PO -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('poBuilder', () => ({
                poNumber: '{{ $nomorPO ?? "" }}',
                poDate: new Date().toISOString().split('T')[0],
                clientName: '{{ $user->nama_perusahaan ?? $user->name }}',
                clientAddress: '',
                clientPhone: '{{ $user->no_wa ?? "" }}',
                clientEmail: '{{ $user->email ?? "" }}',
                clientNpwp: '',
                customNotes: '',
                isLoading: false,

                init() {
                    this.clientNpwp = this.formatNpwp('{{ $user->npwp ?? "" }}');
                },

                formatNpwp(value) {
                    if (!value) return value;
                    let numbers = value.replace(/\D/g, ''); 
                    if (numbers.length >= 15) { 
                        let clean15 = numbers.substring(0, 15);
                        return clean15.replace(/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/, '$1.$2.$3.$4-$5.$6');
                    }
                    return value; 
                },
                
                formatTanggal(dateString) {
                    if(!dateString) return '';
                    let d = new Date(dateString);
                    return ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
                },

                async simpanDanKirimPO() {
                    if(!this.poNumber || !this.clientName) {
                        alert('Harap isi Nomor PO dan Nama Perusahaan terlebih dahulu!');
                        return;
                    }

                    this.isLoading = true;

                    let alamatLengkap = this.clientAddress;
                    if(this.clientPhone) alamatLengkap += `\nTelp/WA: ${this.clientPhone}`;
                    if(this.clientEmail) alamatLengkap += `\nEmail: ${this.clientEmail}`;
                    if(this.clientNpwp) alamatLengkap += `\nNPWP: ${this.clientNpwp}`;

                    let payload = {
                        pesanan_id: '{{ $pengajuan->id ?? "" }}',
                        nomor_po: this.poNumber,
                        tanggal_po: this.poDate,
                        nama_perusahaan: this.clientName,
                        alamat_perusahaan: alamatLengkap,
                        catatan: this.customNotes
                    };

                    try {
                        let response = await fetch('{{ route("klien.po.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        });

                        let result = await response.json();

                        if (result.success) {
                            alert(result.message);
                            window.location.reload();
                        } else {
                            alert('Gagal: ' + result.message);
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan koneksi saat mengirim PO.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        });
    </script>
</x-boma-layout>