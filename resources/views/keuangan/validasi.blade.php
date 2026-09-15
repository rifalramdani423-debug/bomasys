<x-boma-layout>
    <div class="pb-10 flex flex-col space-y-6" x-data="rekonsiliasiManager()">
        
        <!-- Peringatan Sukses / Error -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-5 py-4 rounded-xl text-sm font-bold flex items-center shadow-lg">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-orange-500/10 border border-orange-500/30 text-orange-500 px-5 py-4 rounded-xl text-sm font-bold flex items-center shadow-lg">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white mb-1.5 tracking-tight">Rekonsiliasi & Kendala 🛡️</h1>
                <p class="text-xs sm:text-sm text-zinc-400">Pencocokan dana masuk (Settlement) dan penanganan tagihan bermasalah.</p>
            </div>
        </div>

        <!-- TAB NAVIGATION -->
        <div class="flex overflow-x-auto hide-scroll-bar border-b border-zinc-800 space-x-6 pb-px">
            <button @click="activeTab = 'settlement'" :class="activeTab === 'settlement' ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-zinc-500 hover:text-zinc-300'" class="whitespace-nowrap pb-3 border-b-2 font-bold text-sm transition px-1">
                Dana Masuk (Settlement)
            </button>
            <button @click="activeTab = 'expired'" :class="activeTab === 'expired' ? 'border-amber-500 text-amber-400' : 'border-transparent text-zinc-500 hover:text-zinc-300'" class="whitespace-nowrap pb-3 border-b-2 font-bold text-sm transition px-1">
                VA Kedaluwarsa
            </button>
            <button @click="activeTab = 'denda'" :class="activeTab === 'denda' ? 'border-red-500 text-red-400' : 'border-transparent text-zinc-500 hover:text-zinc-300'" class="whitespace-nowrap pb-3 border-b-2 font-bold text-sm transition px-1">
                Keterlambatan & Denda
            </button>
        </div>

        <!-- TAB KONTEN 1: SETTLEMENT -->
        <div x-show="activeTab === 'settlement'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl mt-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-white flex items-center">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
                    Menunggu Pencocokan Bank
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-800 text-[10px] text-zinc-500 uppercase tracking-wider">
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Pengajuan ID</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Klien / Instansi</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Nominal Bersih</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Waktu Pembayaran</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <!-- Looping data $settlements dari Controller -->
                        @forelse($settlements ?? [] as $item)
                        <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition">
                            <td class="py-4 px-2">
                                <p class="font-bold text-white">{{ $item->pengajuan->nomor_pengajuan ?? 'BOMA-00X' }}</p>
                                <p class="text-[10px] text-emerald-400 font-bold bg-emerald-500/10 px-2 py-0.5 rounded inline-block mt-1">Termin #{{ $item->termin_ke ?? 1 }}</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-bold text-white">{{ $item->pengajuan->user->nama_perusahaan ?? $item->pengajuan->user->name ?? 'Nama Klien' }}</p>
                                <p class="text-[10px] text-zinc-500">Gateway: Midtrans</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-black text-emerald-400">Rp {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="text-xs text-white">{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</p>
                                <p class="text-[10px] text-zinc-500">{{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }} WIB</p>
                            </td>
                            <td class="py-4 px-2 text-right">
                                <form action="#" method="POST" class="inline-block">
                                    @csrf
                                    <button type="button" @click="bukaModalTandai('{{ $item->id ?? '' }}', '{{ $item->pengajuan->nomor_pengajuan ?? '' }}')" class="text-[10px] font-bold bg-zinc-800 hover:bg-emerald-600 hover:text-white text-zinc-300 px-3 py-1.5 rounded-lg transition">
                                        Tandai Masuk Bank
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-500 text-xs">
                                Tidak ada data settlement baru dari Midtrans hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB KONTEN 2: VA KEDALUWARSA -->
        <div x-show="activeTab === 'expired'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl mt-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-white flex items-center">
                    <div class="w-2 h-2 bg-amber-500 rounded-full mr-2 shadow-[0_0_8px_rgba(245,158,11,0.8)]"></div>
                    Daftar VA Batal / Lewat Batas Waktu
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-800 text-[10px] text-zinc-500 uppercase tracking-wider">
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Pengajuan ID & Status</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Klien</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Tagihan</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Kedaluwarsa Sejak</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <!-- Looping data $expired_vas dari Controller -->
                        @forelse($expired_vas ?? [] as $item)
                        <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition">
                            <td class="py-4 px-2">
                                <p class="font-bold text-white">{{ $item->pengajuan->nomor_pengajuan ?? 'BOMA-00X' }}</p>
                                <p class="text-[10px] text-amber-400 font-bold bg-amber-500/10 px-2 py-0.5 rounded inline-block mt-1">Termin #{{ $item->termin_ke ?? 1 }} Batal</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-bold text-white">{{ $item->pengajuan->user->nama_perusahaan ?? $item->pengajuan->user->name ?? 'Klien' }}</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->pengajuan->user->no_wa ?? '') }}" target="_blank" class="text-[10px] text-emerald-500 hover:underline">Hubungi Klien</a>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-black text-white">Rp {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="text-xs text-red-400">{{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}</p>
                            </td>
                            <td class="py-4 px-2 text-right space-x-2 whitespace-nowrap">
                                <form action="#" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold bg-zinc-800 hover:bg-amber-600 hover:text-white text-zinc-300 px-3 py-1.5 rounded-lg transition">
                                        Generate Ulang VA
                                    </button>
                                </form>
                                <form action="#" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold bg-zinc-800 hover:bg-red-600 hover:text-white text-zinc-300 px-3 py-1.5 rounded-lg transition">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-500 text-xs">
                                Seluruh Klien tertib membayar. Tidak ada VA yang kedaluwarsa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB KONTEN 3: KETERLAMBATAN & DENDA -->
        <div x-show="activeTab === 'denda'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl mt-4">
            <div class="flex justify-between items-center mb-6 border-b border-zinc-800 pb-4">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center">
                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></div>
                        Pelanggaran Jatuh Tempo (Termin Lanjutan)
                    </h3>
                    <p class="text-[10px] text-zinc-500 mt-1">Sistem otomatis menghitung estimasi denda sebesar 5% per hari dari nilai termin.</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-800 text-[10px] text-zinc-500 uppercase tracking-wider">
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Pesanan</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Jatuh Tempo</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Hari Telat</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Tagihan Pokok</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2">Estimasi Denda (5%/Hari)</th>
                            <th class="pb-3 font-bold whitespace-nowrap px-2 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <!-- Looping data $overdue_termins dari Controller -->
                        @forelse($overdue_termins ?? [] as $item)
                        @php
                            // Perhitungan Denda 5% per hari telat
                            $hariTelat = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay());
                            $nominalDenda = $item->nominal * 0.05 * $hariTelat;
                            $totalTagihanBaru = $item->nominal + $nominalDenda;
                        @endphp
                        <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition">
                            <td class="py-4 px-2">
                                <p class="font-bold text-white">{{ $item->pengajuan->nomor_pengajuan ?? 'BOMA-00X' }}</p>
                                <p class="text-[10px] text-zinc-500">Termin #{{ $item->termin_ke ?? 2 }}</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-bold text-white">{{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d M Y') }}</p>
                            </td>
                            <td class="py-4 px-2">
                                <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-1 rounded text-xs font-black">{{ $hariTelat }} Hari</span>
                            </td>
                            <td class="py-4 px-2">
                                <p class="text-xs text-zinc-300">Rp {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-2">
                                <p class="font-black text-red-400">+ Rp {{ number_format($nominalDenda, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-2 text-right">
                                <button type="button" @click="bukaModalDenda('{{ $item->id ?? '' }}', '{{ $totalTagihanBaru }}')" class="text-[10px] font-bold bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg transition">
                                    Rilis Tagihan Denda
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-500 text-xs">
                                Luar biasa! Tidak ada Klien yang menunggak cicilan termin saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL AKSI REKONSILIASI -->
        <div x-show="modalOpen" class="fixed inset-0 z-[99999] overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="modalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="modalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-zinc-900 border border-zinc-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                    
                    <form :action="formAction" method="POST">
                        @csrf
                        <div class="px-6 pt-6 pb-4">
                            <h3 class="text-lg font-black text-white mb-2" x-text="modalTitle"></h3>
                            <p class="text-xs text-zinc-400 mb-6" x-text="modalDesc"></p>
                            
                            <div x-show="isDenda" class="bg-red-500/10 border border-red-500/20 p-4 rounded-xl mb-4">
                                <p class="text-[10px] text-red-400 font-bold uppercase tracking-widest mb-1">Total Tagihan Pokok + Denda</p>
                                <p class="text-2xl font-black text-white">Rp <span x-text="formatRupiah(dendaNominal)"></span></p>
                            </div>
                        </div>

                        <div class="bg-zinc-950/80 px-6 py-4 flex justify-end space-x-3 border-t border-zinc-800">
                            <button type="button" @click="modalOpen = false" class="bg-zinc-800 hover:bg-zinc-700 text-white font-bold py-2 px-4 rounded-xl text-sm transition">
                                Batal
                            </button>
                            <button type="submit" class="font-bold py-2 px-6 rounded-xl text-sm transition shadow-lg" :class="isDenda ? 'bg-red-600 hover:bg-red-500 text-white shadow-[0_0_15px_rgba(239,68,68,0.3)]' : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.3)]'">
                                Konfirmasi &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE UNTUK LOGIKA TAB & MODAL -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rekonsiliasiManager', () => ({
                activeTab: 'settlement',
                modalOpen: false,
                formAction: '',
                modalTitle: '',
                modalDesc: '',
                isDenda: false,
                dendaNominal: 0,

                bukaModalTandai(id, orderId) {
                    // Endpoint controller disesuaikan dengan rute yang kamu buat nanti
                    this.formAction = '/keuangan/rekonsiliasi/' + id + '/tandai';
                    this.modalTitle = 'Konfirmasi Mutasi Bank';
                    this.modalDesc = 'Apakah Anda yakin dana untuk pesanan ' + orderId + ' sudah benar-benar masuk ke rekening perusahaan?';
                    this.isDenda = false;
                    this.modalOpen = true;
                },

                bukaModalDenda(id, totalDenda) {
                    this.formAction = '/keuangan/rekonsiliasi/' + id + '/rilis-denda';
                    this.modalTitle = 'Rilis Tagihan Denda';
                    this.modalDesc = 'Sistem akan mengirimkan email dan notifikasi ke Dashboard Klien beserta penambahan tagihan denda keterlambatan.';
                    this.isDenda = true;
                    this.dendaNominal = totalDenda;
                    this.modalOpen = true;
                },

                formatRupiah(angka) {
                    if (!angka) return '0';
                    return new Intl.NumberFormat('id-ID').format(angka);
                }
            }));
        });
        
        // Fungsi fallback copy untuk URL HTTP (Development)
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('Nomor VA disalin!');
                }).catch(function(err) {
                    console.error('Gagal menyalin text: ', err);
                });
            } else {
                let textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('Nomor VA disalin!');
                } catch (err) {
                    console.error('Gagal menyalin text (fallback): ', err);
                }
                textArea.remove();
            }
        }
    </script>
</x-boma-layout>