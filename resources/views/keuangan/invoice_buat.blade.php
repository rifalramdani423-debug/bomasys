<x-boma-layout>
    <div class="pb-10" x-data="invoiceBuilder()">
        
        <!-- ALERT SUKSES DARI REDIRECT VALIDASI -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-5 py-4 rounded-xl text-sm font-bold flex items-center mb-6 shadow-lg">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <h1 class="text-2xl font-black text-white mb-1 flex items-center gap-2">Pembuatan Invoice Resmi 🖨️</h1>
            <p class="text-sm text-zinc-400">Penerbitan bukti bayar dengan ukuran standar A4 secara otomatis.</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- PANEL KONTROL KIRI -->
            <div class="xl:col-span-5 w-full space-y-6">
                <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- 1. Sinkronisasi Data Pesanan -->
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-white mb-1">1. Sinkronisasi Data Pesanan</h3>
                        <p class="text-[11px] text-zinc-400 mb-3 leading-relaxed">Pilih klien untuk memuat alamat lengkap lokasi reklame dan riwayat termin.</p>
                        
                        <form method="GET" action="{{ route('keuangan.invoice.buat') }}" class="w-full">
                            <select name="pesanan_id" onchange="this.form.submit()" class="w-full bg-zinc-950 border border-blue-500/50 text-white text-xs sm:text-sm rounded-xl px-4 py-3.5 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none cursor-pointer appearance-none shadow-inner">
                                <option value="">-- Silakan Pilih Pesanan Klien --</option>
                                @foreach($listPesanan as $item)
                                    <option value="{{ $item->id }}" {{ (isset($pengajuan) && $pengajuan->id == $item->id) ? 'selected' : '' }}>
                                        {{ $item->nomor_pengajuan ?? 'PSN-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) }} - {{ $item->user?->nama_perusahaan ?: $item->user?->name ?: 'Klien Telah Dihapus' }} ({{ $item->status_pengajuan }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- 2. Parameter Kuitansi -->
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center mb-5">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
                            2. Parameter Kuitansi
                        </h3>
                        
                        <!-- Peringatan Jika Tidak Ada Skema -->
                        @if(isset($pengajuan) && $pengajuan->termins->isEmpty())
                            <div class="bg-amber-500/10 border border-amber-500/30 p-4 rounded-xl mb-6 text-center shadow-inner">
                                <p class="text-xs font-bold text-amber-500">Pesanan belum memiliki skema termin.</p>
                            </div>
                        @endif

                        <div class="space-y-5">
                            <!-- PILIH TERMIN (Hanya muncul jika ada termin) -->
                            @if(isset($pengajuan) && $pengajuan->termins->count() > 0)
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1.5">Termin Pembayaran</label>
                                    <select x-model.number="selectedTermin" @change="updateTermin" class="w-full bg-zinc-950 border border-zinc-700 text-white rounded-xl px-4 py-3 text-sm focus:border-emerald-500 outline-none cursor-pointer shadow-inner appearance-none">
                                        <template x-for="t in termins" :key="t.id">
                                            <option :value="t.id" x-text="'Termin ' + t.ke + ' (' + t.persen + '%) - Rp ' + formatRupiahBasic(t.nominal)"></option>
                                        </template>
                                    </select>
                                </div>
                            @endif

                            <div>
                                <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1.5">Nomor Invoice</label>
                                <input type="text" x-model="invNo" readonly class="w-full bg-zinc-950/50 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-500 font-mono text-sm outline-none cursor-not-allowed">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1.5">Tanggal Terbit</label>
                                    <input type="date" x-model="invDate" class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-emerald-500 outline-none [color-scheme:dark] shadow-inner">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1.5">Jatuh Tempo</label>
                                    <input type="date" x-model="dueDate" class="w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-emerald-500 outline-none [color-scheme:dark] shadow-inner">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1.5">Label Status Otomatis</label>
                                <div class="w-full bg-emerald-900/30 border border-emerald-500/50 rounded-xl px-4 py-3 text-emerald-400 font-black text-center uppercase tracking-widest text-sm shadow-inner" x-text="status"></div>
                            </div>

                            <div class="hidden">
                                <input type="number" x-model="nominal" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white text-sm focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        <!-- TOMBOL SUBMIT (DIPERBESAR & LEBIH MENCAT) -->
                        <form action="{{ route('keuangan.invoice.store') }}" method="POST" @submit="prepareSubmit($event)">
                            @csrf
                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id ?? '' }}">
                            <input type="hidden" name="termin_id" :value="selectedTermin">
                            <input type="hidden" name="no_invoice" x-model="invNo">
                            <input type="hidden" name="html_content" id="html_content_input_invoice">

                            <button type="submit" :disabled="!selectedTermin" class="mt-8 w-full bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black py-4 rounded-xl transition shadow-[0_0_25px_rgba(16,185,129,0.4)] flex justify-center items-center cursor-pointer text-base uppercase tracking-widest">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                <span>Simpan & Kirim ke Arsip Klien</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

           <!-- PREVIEW KERTAS (KANAN) - DIKUNCI UKURAN A4 EKSKLUSIF -->
            <div class="xl:col-span-7 w-full overflow-x-auto bg-zinc-950 border border-zinc-800 p-4 rounded-3xl flex justify-center items-start shadow-inner min-h-[80vh]">
                
                <!-- CONTAINER UTAMA UKURAN A4 -->
                <div id="wadah-kertas" class="bg-white text-gray-900 shadow-2xl relative shrink-0" style="width: 210mm; min-height: 297mm; margin: 0 auto; box-sizing: border-box;">
                    <div id="kertas-invoice" style="padding: 20mm; box-sizing: border-box; height: 100%;">
                        
                        <!-- HEADER -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                            <div>
                                <h1 style="font-size: 1.75rem; font-weight: 900; color: #111827; margin-bottom: 0.5rem; font-family: 'Times New Roman', Times, serif;">CV. BOMA CIPTA CITRA</h1>
                                <p style="font-size: 0.7rem; color: #6b7280; letter-spacing: 0.025em; font-family: sans-serif; line-height: 1.5;">
                                    Jl. Wastukencana No. 99A, Bandung 40116<br>
                                    marketing@bomaadvertising.com • 0812-3456-7890<br>
                                    NPWP 01.234.567.8-123.000
                                </p>
                            </div>
                            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end;">
                                <div style="background-color: #111827; color: white; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.1em; padding: 0.4rem 0.8rem; text-transform: uppercase; font-family: sans-serif; margin-bottom: 0.5rem;">INVOICE</div>
                                <p style="font-size: 0.7rem; font-weight: bold; color: #6b7280; font-family: sans-serif;" x-text="invNo"></p>
                            </div>
                        </div>

                        <!-- TANGGAL & STATUS -->
                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; font-family: sans-serif;">
                            <div>
                                <p style="font-size: 8px; font-weight: 900; color: #b49e62; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Tanggal Terbit</p>
                                <p style="font-size: 0.8rem; font-weight: bold; color: #111827;" x-text="formatDate(invDate)"></p>
                            </div>
                            <div>
                                <p style="font-size: 8px; font-weight: 900; color: #b49e62; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Jatuh Tempo Termin</p>
                                <p style="font-size: 0.8rem; font-weight: bold; color: #111827;" x-text="formatDate(dueDate)"></p>
                            </div>
                            <div>
                                <p style="font-size: 8px; font-weight: 900; color: #b49e62; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Status Kuitansi</p>
                                <p style="font-size: 0.8rem; font-weight: 900; color: #059669; text-transform: uppercase;" x-text="status"></p>
                            </div>
                        </div>

                        <!-- DITAGIHKAN KEPADA -->
                        <div style="background-color: #fcfbf9; border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #f0ebd8;">
                            <p style="font-size: 8px; font-weight: 900; color: #b49e62; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; font-family: sans-serif;">Ditagihkan Kepada:</p>
                            <h3 style="font-size: 1rem; font-weight: bold; color: #111827; margin-bottom: 0.25rem; text-transform: uppercase;">{{ $pengajuan->user->nama_perusahaan ?? ($pengajuan->user->name ?? 'NAMA KLIEN / PERUSAHAAN') }}</h3>
                            <p style="font-size: 0.7rem; color: #4b5563; font-family: sans-serif; line-height: 1.5;">
                                Penanggung Jawab: {{ $pengajuan->user->name ?? '-' }}<br>
                                NPWP Instansi: {{ $pengajuan->user->npwp ?? 'Tidak Terdaftar' }}<br>
                                Kontak/WhatsApp: {{ $pengajuan->user->no_wa ?? '-' }} ({{ $pengajuan->user->email ?? 'email@klien.com' }})
                            </p>
                        </div>

                        <!-- TABEL TAGIHAN TERMIN -->
                        <div style="font-family: sans-serif; margin-bottom: 1.5rem;">
                            <table style="width: 100%; font-size: 0.7rem; text-align: left; margin-bottom: 0.5rem; border-collapse: collapse;">
                                <thead style="border-top: 1px solid #f0ebd8; border-bottom: 1px solid #f0ebd8; background-color: #faf9f5;">
                                    <tr>
                                        <th style="padding: 0.6rem 0.8rem; font-weight: 900; color: #a59368; text-transform: uppercase; letter-spacing: 0.05em;">Deskripsi Pembayaran Termin</th>
                                        <th style="padding: 0.6rem 0.8rem; font-weight: 900; color: #a59368; text-transform: uppercase; letter-spacing: 0.05em; text-align: right;">Subtotal / Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 1rem 0.8rem; vertical-align: top;">
                                            <p style="font-weight: bold; color: #1f2937; font-size: 0.8rem; margin-bottom: 0.25rem;">
                                                Pembayaran Sewa Reklame <span style="color: #b49e62;" x-text="terminName"></span>
                                            </p>
                                            <p style="font-size: 0.65rem; color: #6b7280; margin-bottom: 0.5rem;">Sesuai Kesepakatan ID Pesanan: <span style="font-weight: bold;">{{ $pengajuan->nomor_pengajuan ?? ('PSN-'.str_pad($pengajuan->id ?? '0', 4, '0', STR_PAD_LEFT)) }}</span></p>
                                            
                                            <!-- Rincian Harga Deal -->
                                            <div style="background-color: #faf9f5; border: 1px solid #e5e7eb; border-radius: 0.4rem; padding: 0.6rem; margin-top: 0.75rem; margin-bottom: 0.5rem;">
                                                <p style="font-size: 8px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.4rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.2rem;">Rincian Harga Kesepakatan (Total):</p>
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <template x-for="rincian in rincianKomponen" :key="rincian.nama_item">
                                                        <tr>
                                                            <td style="padding: 0.2rem 0; font-size: 0.65rem; color: #4b5563;" x-text="rincian.nama_item"></td>
                                                            <td style="padding: 0.2rem 0; text-align: right; font-size: 0.65rem; color: #111827;" x-text="formatRupiah(rincian.harga_item)"></td>
                                                        </tr>
                                                    </template>
                                                    <tr>
                                                        <td style="padding-top: 0.4rem; font-weight: bold; font-size: 0.7rem; color: #111827; border-top: 1px dashed #d1d5db;">TOTAL DEAL (KONTRAK):</td>
                                                        <td style="padding-top: 0.4rem; text-align: right; font-weight: bold; font-size: 0.7rem; color: #111827; border-top: 1px dashed #d1d5db;" x-text="formatRupiah(totalDealHarga)"></td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <p style="font-size: 9px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.2rem; margin-top:0.75rem;">Cakupan Titik Reklame:</p>
                                            <ul style="font-size: 0.65rem; color: #4b5563; padding-left: 1rem; margin: 0; line-height: 1.4;">
                                                @if(isset($pengajuan) && $pengajuan->details->count() > 0)
                                                    @foreach($pengajuan->details as $d)
                                                        <li>Titik Kode: <b>{{ $d->kode_titik }}</b></li>
                                                    @endforeach
                                                @else
                                                    <li>Daftar titik reklame akan otomatis dimuat setelah pesanan dipilih.</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td style="padding: 1rem 0.8rem; text-align: right; vertical-align: top; font-weight: bold; color: #111827; font-size: 0.8rem;" x-text="formatRupiah(nominal)"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- SUMMARY KEUANGAN -->
                        <div style="display: flex; justify-content: flex-end; font-family: sans-serif; margin-bottom: 2rem;">
                            <div style="width: 50%;">
                                <table style="width: 100%; font-size: 0.8rem; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 0.75rem 0; font-weight: 900; color: #a59368; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.7rem; border-bottom: 2px solid #111827;">Total Tagihan (Nett)</td>
                                        <td style="padding: 0.75rem 0; text-align: right; font-weight: 900; font-size: 1.25rem; color: #059669; border-bottom: 2px solid #111827;" x-text="formatRupiah(nominal)"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- INFORMASI PEMBAYARAN & STEMPEL -->
                        <div style="font-family: sans-serif; border-top: 2px solid #f0ebd8; padding-top: 1.25rem; display: flex; justify-content: space-between; align-items: flex-start; margin-top: auto;">
                            <div>
                                <h4 style="font-size: 0.8rem; font-weight: bold; color: #111827; margin-bottom: 0.4rem; margin-top: 0;">Informasi Validasi</h4>
                                <p style="font-size: 0.65rem; color: #4b5563; line-height: 1.4; margin: 0;">
                                    Pembayaran transfer ini harus tervalidasi melalui:<br>
                                    Bank BCA / Mandiri - a.n. CV. Boma Cipta Citra<br>
                                    Dokumen ini dicetak pada <span x-text="formatDate(invDate)"></span>
                                </p>
                            </div>
                            <div style="text-align: center; width: 10rem;">
                                <!-- LINGKARAN STEMPEL DINAMIS -->
                                <div style="width: 5.5rem; height: 5.5rem; margin: 0 auto 0.4rem auto; border: 3px solid rgba(16, 185, 129, 0.4); border-radius: 9999px; display: flex; align-init: center; justify-content: center; transform: rotate(-15deg);">
                                    <span style="color: #10b981; font-weight: 900; font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; text-align: center; line-height: 1.2; display: flex; align-items: center; justify-content: center;" x-html="stampText">
                                    </span>
                                </div>
                                <p style="font-size: 0.65rem; font-weight: bold; color: #1f2937; margin: 0;">Divisi Keuangan BOMA</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STYLE KHUSUS PRINT -->
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            body * { visibility: hidden; }
            #wadah-kertas, #wadah-kertas * { visibility: visible; }
            #wadah-kertas { position: absolute; left: 0; top: 0; width: 210mm; height: 297mm; box-shadow: none; margin: 0; }
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('invoiceBuilder', () => ({
                invNo: '{{ $nomorInvoice ?? "INV/BOMA/000" }}',
                invDate: new Date().toISOString().split('T')[0],
                dueDate: new Date().toISOString().split('T')[0],
                status: 'MENUNGGU DATA',
                stampText: 'VALIDASI<br><span style="font-size:0.5rem;">SISTEM</span>', 
                
                terminName: '(Pilih Termin)',
                nominal: 0,
                
                // MENGAMBIL DATA RINCIAN DARI DATABASE (JSON)
                rincianKomponen: @json(isset($pengajuan) && $pengajuan->catatan_harga ? json_decode($pengajuan->catatan_harga) : []),
                totalDealHarga: {{ $pengajuan->harga_final ?? 0 }},

                termins: [
                    @if(isset($pengajuan) && $pengajuan->termins)
                        @foreach($pengajuan->termins as $t)
                            {
                                id: {{ $t->id }},
                                ke: {{ $t->termin_ke }},
                                persen: {{ $t->persentase }},
                                nominal: {{ $t->nominal }},
                                tanggal: '{{ \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('Y-m-d') }}'
                            },
                        @endforeach
                    @endif
                ],
                
                selectedTermin: '{{ $termin_id ?? "" }}',

                init() {
                    if(this.termins.length > 0) {
                        if (!this.selectedTermin) {
                            this.selectedTermin = this.termins[0].id;
                        } else {
                            this.selectedTermin = Number(this.selectedTermin);
                        }
                        this.updateTermin();
                    } else {
                        this.status = 'MENUNGGU DATA';
                    }
                },

                updateTermin() {
                    let t = this.termins.find(x => x.id == this.selectedTermin);
                    let totalJumlahTermin = this.termins.length;

                    if(t) {
                        this.terminName = '(Termin ' + t.ke + ' - ' + t.persen + '%)';
                        this.nominal = t.nominal;
                        this.dueDate = t.tanggal;
                        
                        if (t.ke === totalJumlahTermin) {
                            this.status = 'LUNAS (PELUNASAN TOTAL)';
                            this.stampText = 'LUNAS<br><span style="font-size:0.5rem;">PELUNASAN</span>';
                        } else {
                            this.status = 'LUNAS - TERMIN ' + t.ke;
                            this.stampText = 'LUNAS<br>TERMIN ' + t.ke;
                        }
                    }
                },

                formatDate(dateString) {
                    if(!dateString) return '';
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                },

                formatRupiahBasic(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka || 0);
                },

                formatRupiah(angka) {
                    return 'Rp ' + this.formatRupiahBasic(angka);
                },

                prepareSubmit(event) {
                    if(!this.selectedTermin) {
                        event.preventDefault(); 
                        alert('Silakan pilih pesanan dan termin terlebih dahulu!');
                        return;
                    }

                    document.getElementById('html_content_input_invoice').value = document.getElementById('kertas-invoice').innerHTML;
                }
            }));
        });
    </script>
</x-boma-layout>