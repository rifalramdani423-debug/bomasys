<x-boma-layout>
    <div class="flex flex-col space-y-6 pb-10 relative z-0">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white mb-1.5 tracking-tight">Daftar Tagihan & Termin 💸</h1>
                <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed max-w-2xl">Kelola skema pembayaran dan selesaikan tagihan via Virtual Account Mandiri.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 px-4 py-3.5 rounded-xl text-xs sm:text-sm font-bold flex items-start sm:items-center shadow-lg gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="leading-relaxed">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-4 md:space-y-6">
            @forelse($tagihan_list as $pesanan)
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-xl transition-all duration-300">
                    
                    <div onclick="toggleFolder({{ $pesanan->id }})" class="p-4 sm:p-5 cursor-pointer hover:bg-zinc-800/40 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-3 sm:gap-4 w-full">
                            <div class="bg-zinc-950 p-2.5 sm:p-3 rounded-xl border border-zinc-800 shrink-0 hidden sm:flex items-center justify-center shadow-inner">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5 md:mb-2">
                                    <span class="bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 px-2 py-0.5 rounded-md text-[9px] md:text-[10px] font-black uppercase tracking-widest shrink-0">{{ $pesanan->status_pengajuan }}</span>
                                    <span class="text-[9px] md:text-[10px] text-zinc-500 font-mono inline-block">ID: {{ $pesanan->nomor_pengajuan ?? 'BOMA-' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h3 class="text-white font-black text-base sm:text-lg md:text-xl mb-1 truncate">Total Rp {{ number_format($pesanan->harga_final ?? $pesanan->estimasi_harga, 0, ',', '.') }}</h3>
                                <div class="text-[10px] sm:text-xs text-zinc-400">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 shrink-0 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Tgl: {{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-end text-[10px] sm:text-xs font-bold text-zinc-500 gap-3 w-full md:w-auto pt-3 md:pt-0 border-t border-zinc-800 md:border-t-0">
                            <span class="bg-zinc-950 border border-zinc-800 px-3 py-1.5 rounded-lg shadow-inner">{{ $pesanan->termins->count() }} Termin</span>
                            <div class="bg-zinc-800/50 p-1.5 rounded-lg text-white">
                                <svg id="icon-arrow-{{ $pesanan->id }}" class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div id="folder-content-{{ $pesanan->id }}" class="hidden border-t border-zinc-800 bg-zinc-950/40 p-4 sm:p-5">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @if($pesanan->termins->isEmpty())
                                <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 text-center flex flex-col items-center justify-center shadow-inner">
                                    <p class="text-xs sm:text-sm text-zinc-400 mb-4 font-medium max-w-sm">Anda belum mengatur skema cicilan pembayaran.</p>
                                    <a href="{{ route('klien.termin.buat', $pesanan->id) }}" class="inline-flex bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-6 py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                        Atur Skema Sekarang &rarr;
                                    </a>
                                </div>
                            @else
                                @foreach($pesanan->termins as $termin)
                                    @php
                                        $denda = 0;
                                        $hariTelat = 0;
                                        $isTelat = false;
                                        $sekarang = \Carbon\Carbon::now()->startOfDay();
                                        $jatuhTempo = \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->startOfDay();

                                        // Cek Keterlambatan
                                        if ($sekarang->greaterThan($jatuhTempo) && !in_array($termin->status_termin, ['Lunas', 'Selesai'])) {
                                            $hariTelat = $sekarang->diffInDays($jatuhTempo);
                                            $isTelat = true;
                                            if ($termin->termin_ke > 1) {
                                                $denda = $termin->nominal * 0.05 * $hariTelat;
                                            }
                                        }
                                        
                                        $tampilkanVa = false;
                                        if ($termin->termin_ke == 1) {
                                            $tampilkanVa = true;
                                        } else {
                                            $terminSebelumnya = $pesanan->termins->where('termin_ke', $termin->termin_ke - 1)->first();
                                            // LOGIKA DIPERBAIKI: Mengakui status Lunas DAN Selesai
                                            if ($terminSebelumnya && in_array($terminSebelumnya->status_termin, ['Lunas', 'Selesai'])) {
                                                $tampilkanVa = true;
                                            }
                                        }
                                    @endphp

                                    <div class="bg-zinc-900 border {{ $isTelat && $termin->termin_ke > 1 ? 'border-red-500/50 ring-1 ring-red-500/20' : 'border-zinc-800' }} rounded-xl p-4 sm:p-5 flex flex-col justify-between gap-4 shadow-sm">
                                        
                                        <div class="flex items-start gap-3 sm:gap-4">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-500 font-black flex items-center justify-center shrink-0 text-xs sm:text-sm shadow-inner">
                                                #{{ $termin->termin_ke }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-white font-black text-sm sm:text-base flex items-center flex-wrap gap-1.5 sm:gap-2">
                                                    Rp {{ number_format($termin->nominal, 0, ',', '.') }} 
                                                    <span class="text-[9px] sm:text-[10px] text-zinc-500 font-bold bg-zinc-950 px-1.5 py-0.5 rounded border border-zinc-800">{{ $termin->persentase }}%</span>
                                                </div>
                                                <div class="text-[10px] sm:text-xs mt-1 font-medium text-zinc-400">
                                                    Tempo: <span class="{{ $isTelat ? 'text-red-400' : 'text-zinc-300' }}">{{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-full mt-2 pt-3 border-t border-zinc-800/60">
                                            
                                            <!-- LOGIKA DIPERBAIKI: Menangkap Lunas dan Selesai -->
                                            @if(in_array($termin->status_termin, ['Lunas', 'Selesai']))
                                                <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-3 w-full">
                                                    <span class="w-full sm:w-auto bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 px-4 py-2.5 rounded-xl text-[10px] sm:text-xs font-bold flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        PEMBAYARAN SELESAI
                                                    </span>
                                                    @if($termin->bukti_pembayaran)
                                                        <a href="{{ asset('storage/' . $termin->bukti_pembayaran) }}" target="_blank" class="text-[10px] text-zinc-500 hover:text-white underline transition">Lihat Bukti</a>
                                                    @endif
                                                </div>

                                            @elseif($termin->status_termin === 'Menunggu Verifikasi Admin')
                                                <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-3 w-full">
                                                    <span class="w-full sm:w-auto bg-blue-500/10 text-blue-400 border border-blue-500/30 px-4 py-2.5 rounded-xl text-[10px] sm:text-xs font-bold flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                        Sedang Diverifikasi Keuangan
                                                    </span>
                                                    @if($termin->bukti_pembayaran)
                                                        <a href="{{ asset('storage/' . $termin->bukti_pembayaran) }}" target="_blank" class="text-[10px] text-zinc-500 hover:text-white underline transition">Lihat File Bukti</a>
                                                    @endif
                                                </div>

                                            @elseif($termin->status_termin === 'Menunggu Revisi')
                                                <div class="flex flex-col w-full">
                                                    <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-3 mb-3 w-full">
                                                        <p class="text-[10px] sm:text-xs font-black text-orange-400 flex items-center mb-1">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                            BUTUH REVISI BUKTI
                                                        </p>
                                                        <p class="text-[10px] sm:text-[11px] text-zinc-300 leading-snug">{{ str_replace('REVISI KEUANGAN: ', '', $termin->keterangan) }}</p>
                                                    </div>
                                                    <form action="{{ route('klien.termin.upload', $termin->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full">
                                                        @csrf
                                                        <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-[10px] text-zinc-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-zinc-950 file:text-zinc-300 hover:file:bg-zinc-800 cursor-pointer transition border border-zinc-800 rounded-lg focus:outline-none focus:border-orange-500">
                                                        <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white text-[10px] sm:text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-[0_0_15px_rgba(249,115,22,0.3)] w-full sm:w-auto shrink-0 mt-2 sm:mt-0">
                                                            Kirim Revisi
                                                        </button>
                                                    </form>
                                                </div>

                                            @elseif($tampilkanVa)
                                                @if($termin->nomor_va)
                                                    <div class="bg-zinc-950 border border-blue-500/30 rounded-xl p-4 w-full relative overflow-hidden mt-1 shadow-[0_0_15px_rgba(59,130,246,0.05)]">
                                                        <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                                                        
                                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-3 pb-3 border-b border-zinc-800">
                                                            <span class="text-[10px] sm:text-[11px] font-black text-blue-400 uppercase tracking-widest flex items-center shrink-0">
                                                                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                                                Mandiri Virtual Account
                                                            </span>
                                                            <span class="text-[9px] text-zinc-500 font-medium animate-pulse">Menunggu Pembayaran Otomatis...</span>
                                                        </div>
                                                        
                                                        <div class="mb-2">
                                                            <p class="text-[9px] text-zinc-500 uppercase tracking-wider mb-1.5">Nomor Rekening VA:</p>
                                                            <div class="flex items-center justify-between bg-black/60 rounded-xl p-3 sm:p-4 border border-zinc-800 shadow-inner">
                                                                <span class="text-sm sm:text-xl md:text-2xl font-black text-emerald-400 tracking-widest font-mono truncate" id="va-{{ $termin->id }}">
                                                                    {{ substr($termin->nomor_va, 0, 4) }} {{ substr($termin->nomor_va, 4, 4) }} {{ substr($termin->nomor_va, 8) }}
                                                                </span>
                                                                <button onclick="copyToClipboard('{{ $termin->nomor_va }}')" class="text-zinc-500 hover:text-white hover:bg-zinc-800 p-2 rounded-lg transition shrink-0 ml-2 sm:ml-3" title="Salin Nomor">
                                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <p class="text-[9px] sm:text-[10px] text-zinc-400 font-medium relative z-10 leading-relaxed">Sistem akan memverifikasi pembayaran Anda dalam hitungan detik setelah transfer berhasil. Tidak perlu mengunggah bukti.</p>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center p-4 sm:p-5 bg-zinc-950 rounded-xl border border-zinc-800 shadow-inner">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-zinc-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                        <span class="text-[10px] sm:text-xs text-zinc-500 font-medium">Sedang menyiapkan Nomor Virtual Account...</span>
                                                    </div>
                                                @endif

                                            @else
                                                <div class="flex items-center justify-center p-4 sm:p-5 bg-zinc-950/50 rounded-xl border border-zinc-800/50">
                                                    <span class="text-[10px] sm:text-xs text-zinc-600 font-medium flex items-center">
                                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                        Menunggu Termin Sebelumnya Lunas
                                                    </span>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 sm:p-12 text-center flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-zinc-800 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-xs sm:text-sm text-zinc-500">Keren! Anda tidak memiliki tagihan sewa saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

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