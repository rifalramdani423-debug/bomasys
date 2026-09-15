@php
    $hariIni = \Carbon\Carbon::now()->format('Y-m-d');
    $batasDp = \Carbon\Carbon::now()->addDays(3)->format('Y-m-d');
    $selesaiSewa = $pesanan->selesai_sewa ?? \Carbon\Carbon::now()->addMonths(1);
    $batasAkhir = \Carbon\Carbon::parse($selesaiSewa)->addDays(14)->format('Y-m-d');
@endphp

<x-boma-layout>
    <div x-data="terminForm({{ $pesanan->harga_final ?? $pesanan->estimasi_harga ?? 140000000 }}, '{{ $hariIni }}', '{{ $batasDp }}', '{{ $batasAkhir }}')" class="flex flex-col space-y-4 pb-10">
        
        <div class="border-b border-zinc-800 pb-3 flex items-end justify-between">
            <div>
                <h1 class="text-2xl font-black text-white mb-1">Pengaturan Keuangan & Termin 💳</h1>
                <p class="text-xs text-zinc-400">Atur skema cicilan pembayaran sesuai hasil negosiasi harga final.</p>
            </div>
            <a href="{{ route('klien.keuangan') }}" class="text-xs font-bold text-zinc-500 hover:text-white transition">&larr; Kembali</a>
        </div>

        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 flex items-start space-x-3 shadow-lg">
            <div class="bg-red-500/20 p-2 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-black text-red-500 mb-1 uppercase tracking-wide">Ketentuan Pembayaran</h4>
                <div class="text-[10px] text-zinc-300 leading-relaxed space-y-1">
                    <ul class="list-disc pl-3 space-y-0.5 marker:text-red-500">
                        <li><b>Tanda Jadi (Termin 1):</b> Maksimal <b class="text-red-400">3x24 Jam</b>. Lewat batas = Pengajuan Dibatalkan.</li>
                        <li><b>Batas Maksimal Pelunasan:</b> Lunas maksimal 14 hari setelah masa sewa habis.</li>
                        <li><b>Denda:</b> Keterlambatan Termin ke-2 dst dikenakan denda 5% per hari.</li>
                    </ul>
                </div>
            </div>
        </div>

        @if($errors->any() || session('error'))
            <div class="bg-red-500/10 border border-red-500/30 p-4 rounded-xl shadow-lg animate-pulse">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h4 class="text-sm font-black text-red-500">Gagal Menyimpan Data!</h4>
                </div>
                <ul class="list-disc pl-9 text-xs text-red-400 space-y-1 font-medium">
                    @if(session('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest mb-1.5 inline-block">Deal Disetujui</span>
                <h2 class="text-white font-black text-lg">
                    Total: <span class="text-emerald-400">Rp <span x-text="formatRupiahSingkat(hargaTotal)"></span></span>
                </h2>
            </div>
            <div class="text-left md:text-right text-[10px] space-y-1">
                <p class="text-zinc-400">Masa Sewa: <span class="text-zinc-200 font-bold">{{ \Carbon\Carbon::parse($pesanan->mulai_sewa ?? '2026-08-27')->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pesanan->selesai_sewa ?? '2026-09-27')->format('d M Y') }}</span></p>
                <p class="text-zinc-500">Batas Pelunasan (+14 Hari): <span class="text-red-400 font-bold">{{ \Carbon\Carbon::parse($batasAkhir)->format('d M Y') }}</span></p>
            </div>
        </div>

        <form action="{{ route('klien.termin.store', $pesanan->id ?? 1) }}" method="POST" @submit="validateForm">
            @csrf
            
            <div class="space-y-3">
                <template x-for="(row, index) in rows" :key="index">
                    <div class="bg-zinc-950/50 border border-zinc-800 rounded-xl p-3 transition hover:border-zinc-700">
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                            
                            <div class="md:col-span-1 flex md:justify-center">
                                <div class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-500 font-black flex items-center justify-center text-xs">
                                    #<span x-text="index + 1"></span>
                                </div>
                            </div>

                            <div class="md:col-span-2 relative">
                                <label class="block text-[9px] font-bold text-zinc-500 mb-1 uppercase tracking-wider">Persen (%)</label>
                                <div class="relative">
                                    <input type="number" x-model="row.persentase" @input="updatePersentase(index)" :name="`termin[${index}][persentase]`" min="0" max="100" required 
                                        class="w-full bg-zinc-900 border border-zinc-800 rounded-lg pl-3 pr-7 py-2 text-white font-bold text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-zinc-500 font-bold text-xs">%</span>
                                </div>
                            </div>

                            <div class="md:col-span-2 relative">
                                <label class="block text-[9px] font-bold text-zinc-500 mb-1 uppercase tracking-wider">Nominal Rupiah</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 font-bold text-xs">Rp</span>
                                    <input type="text" 
                                        :value="formatRupiahSingkat(row.nominal)" 
                                        @input="updateNominal(index, $event.target.value)"
                                        class="w-full bg-zinc-900 border border-zinc-800 rounded-lg pl-8 pr-3 py-2 text-emerald-400 font-bold text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition">
                                </div>
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-[9px] font-bold text-zinc-500 mb-1 uppercase tracking-wider flex justify-between">
                                    Jatuh Tempo 
                                    <span x-show="index === 0" class="text-red-400 font-black">(Maks 3 Hari)</span>
                                </label>
                                <input type="date" x-model="row.tanggal" :name="`termin[${index}][tanggal]`" required
                                    :min="hariIni" :max="index === 0 ? batasDp : batasAkhir"
                                    class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-zinc-300 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition [color-scheme:dark]">
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-[9px] font-bold text-zinc-500 mb-1 uppercase tracking-wider">Catatan</label>
                                <input type="text" x-model="row.keterangan" :name="`termin[${index}][keterangan]`" placeholder="Misal: Pelunasan akhir..."
                                    class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-zinc-300 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition placeholder:text-zinc-600">
                            </div>

                            <div class="md:col-span-1 flex justify-end md:justify-center">
                                <button type="button" @click="removeRow(index)" x-show="rows.length > 1" class="p-2 text-zinc-500 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-4 flex flex-col md:flex-row items-center justify-between bg-zinc-900 border border-zinc-800 p-3 rounded-xl shadow-lg gap-4">
                <div class="flex items-center gap-4">
                    <button type="button" @click="addRow" class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-[11px] font-bold px-3 py-2 rounded-lg transition">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Termin
                    </button>
                    <div class="text-[11px] font-medium text-zinc-400">
                        Total Persentase: 
                        <span class="font-black ml-1 text-sm" :class="totalPersentase === 100 ? 'text-emerald-400' : 'text-red-400'">
                            <span x-text="totalPersentase"></span>%
                        </span>
                    </div>
                </div>
                
                <button type="submit" 
                    :disabled="totalPersentase !== 100"
                    :class="totalPersentase === 100 ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.3)]' : 'bg-zinc-800 text-zinc-500 cursor-not-allowed border border-zinc-700'"
                    class="w-full md:w-auto text-xs font-bold px-6 py-2.5 rounded-lg transition flex items-center justify-center">
                    Simpan Skema
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('terminForm', (hargaTotal, hariIni, batasDp, batasAkhir) => ({
                hargaTotal: hargaTotal,
                hariIni: hariIni,
                batasDp: batasDp,
                batasAkhir: batasAkhir,
                rows: [
                    { persentase: 50, nominal: (hargaTotal * 50 / 100), tanggal: '', keterangan: '' },
                    { persentase: 50, nominal: (hargaTotal * 50 / 100), tanggal: '', keterangan: '' }
                ],
                
                get totalPersentase() {
                    let sum = this.rows.reduce((sum, row) => sum + (parseFloat(row.persentase) || 0), 0);
                    return parseFloat(sum.toFixed(2));
                },
                
                formatRupiahSingkat(angka) {
                    if (!angka && angka !== 0) return '0';
                    return new Intl.NumberFormat('id-ID').format(angka);
                },

                koreksiBarisAkhir(index) {
                    let lastIndex = this.rows.length - 1;
                    if (index !== lastIndex && this.rows.length > 1) {
                        let sumOther = 0;
                        this.rows.forEach((r, i) => {
                            if (i !== lastIndex) sumOther += (parseFloat(r.persentase) || 0);
                        });
                        let sisa = 100 - sumOther;
                        this.rows[lastIndex].persentase = parseFloat(Math.max(0, sisa).toFixed(2));
                        this.rows[lastIndex].nominal = Math.round((this.hargaTotal * (this.rows[lastIndex].persentase / 100)));
                    }
                },

                updatePersentase(index) {
                    let p = parseFloat(this.rows[index].persentase) || 0;
                    this.rows[index].nominal = Math.round((this.hargaTotal * (p / 100)));
                    this.koreksiBarisAkhir(index);
                },

                updateNominal(index, value) {
                    let angkaMurni = parseInt(value.replace(/[^0-9]/g, '')) || 0;
                    this.rows[index].nominal = angkaMurni;
                    let hitungPersen = (angkaMurni / this.hargaTotal) * 100;
                    this.rows[index].persentase = parseFloat(hitungPersen.toFixed(2));
                    this.koreksiBarisAkhir(index);
                },
                
                addRow() {
                    this.rows.push({ persentase: 0, nominal: 0, tanggal: '', keterangan: '' });
                    this.bagiRataPersentase();
                },
                
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                        this.bagiRataPersentase();
                    }
                },

                bagiRataPersentase() {
                    let jumlahBaris = this.rows.length;
                    let bagiPersen = 100 / jumlahBaris; 
                    
                    this.rows.forEach((row, idx) => {
                        row.persentase = parseFloat(bagiPersen.toFixed(2)); 
                        if (idx === jumlahBaris - 1) {
                            let totalSementara = parseFloat((bagiPersen.toFixed(2)) * (jumlahBaris - 1));
                            row.persentase = parseFloat((100 - totalSementara).toFixed(2));
                        }
                        row.nominal = Math.round((this.hargaTotal * (row.persentase / 100)));
                    });
                },

                validateForm(e) {
                    if (Math.abs(this.totalPersentase - 100) > 0.1) {
                        e.preventDefault(); 
                        alert('Gagal menyimpan! Total akumulasi persentase termin harus tepat 100%. Saat ini: ' + this.totalPersentase + '%');
                    }
                }
            }))
        })
    </script>
</x-boma-layout>