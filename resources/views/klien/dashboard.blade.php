<x-boma-layout>
    <div class="flex flex-col space-y-6 md:space-y-8">

        <div>
            <h1 class="mb-2 text-2xl font-black text-white sm:text-3xl">Selamat Datang, {{ explode(' ', $user->name)[0] }}! 👋</h1>
            <p class="text-sm text-zinc-400">Pantau status penyewaan billboard dan tagihanmu di sini.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="relative overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 p-5 shadow-lg sm:p-6">
                <div class="absolute -bottom-4 -right-4 h-24 w-24 rounded-full bg-zinc-700/20 blur-xl"></div>
                <div class="relative z-10 flex items-center justify-between gap-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Total Pesanan</p>
                    <svg class="h-5 w-5 shrink-0 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                </div>
                <h3 class="relative z-10 mt-2 text-3xl font-black text-white">
                    <span data-live="klien_total_pesanan">{{ $totalPesanan }}</span>
                    <span class="text-sm font-normal text-zinc-500">Titik</span>
                </h3>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-emerald-500/30 bg-zinc-900 p-5 shadow-lg sm:p-6">
                <div class="absolute -bottom-4 -right-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-xl"></div>
                <div class="relative z-10 flex items-center justify-between gap-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-500">Billboard Aktif</p>
                    <svg class="h-5 w-5 shrink-0 text-emerald-500/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="relative z-10 mt-2 text-3xl font-black text-white">
                    <span data-live="klien_aktif">{{ $pesananAktif }}</span>
                    <span class="text-sm font-normal text-emerald-500/70">Tersewa</span>
                </h3>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-amber-500/30 bg-zinc-900 p-5 shadow-lg sm:p-6">
                <div class="absolute -bottom-4 -right-4 h-24 w-24 rounded-full bg-amber-500/10 blur-xl"></div>
                <div class="relative z-10 flex items-center justify-between gap-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Menunggu Pembayaran</p>
                    <svg class="h-5 w-5 shrink-0 text-amber-500/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="relative z-10 mt-2 break-all text-2xl font-black text-white sm:text-3xl">
                    <span class="text-base font-normal sm:text-lg">Rp</span>
                    <span data-live="klien_tagihan">{{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </h3>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl border border-zinc-800 bg-zinc-950 p-5 sm:p-6 md:p-8">
            <div class="pointer-events-none absolute right-0 top-0 p-4 opacity-10">
                <svg class="h-32 w-32 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>

            <h2 class="mb-6 flex items-center text-lg font-black text-white sm:text-xl">
                <svg class="mr-2 h-6 w-6 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Panduan Penyewaan (SOP)
            </h2>

            @php
                $sop = [
                    ['Pilih & Ajukan',   'Pilih titik billboard di menu Map dan ajukan penyewaan.'],
                    ['Upload Data Diri', 'Lengkapi dokumen KTP dan NPWP Anda untuk administrasi.'],
                    ['Skema Termin',     'Tentukan kesepakatan jumlah termin pembayaran.'],
                    ['Pembuatan PO',     'Sistem akan menerbitkan Purchase Order (PO) resmi.'],
                    ['Upload Bukti',     'Bayar tagihan termin, upload bukti transfer.'],
                    ['Validasi & Aktif', 'Tim memvalidasi, status titik di Map berubah menjadi merah.'],
                ];
            @endphp

            <div class="relative z-10 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                @foreach($sop as $i => $langkah)
                    <div class="flex flex-col space-y-2">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-emerald-500/30 bg-emerald-500/20 font-black text-emerald-400">{{ $i + 1 }}</div>
                        <h4 class="text-sm font-bold text-white">{{ $langkah[0] }}</h4>
                        <p class="text-xs leading-relaxed text-zinc-400">{{ $langkah[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h2 class="mb-4 text-lg font-black text-white sm:text-xl">Pelacakan Pesanan 📦</h2>

            <div class="space-y-4">
                @forelse($pengajuans as $pesanan)
                    @php
                        $step          = 1;
                        $adaTermin     = $pesanan->termins->count() > 0;
                        $adaBuktiBayar = $pesanan->termins->where('bukti_pembayaran', '!=', null)->count() > 0;
                        $semuaLunas    = $pesanan->status_pengajuan === 'Lunas / Aktif';

                        if ($semuaLunas)          { $step = 4; }
                        elseif ($adaBuktiBayar)   { $step = 3; }
                        elseif ($adaTermin)       { $step = 2; }

                        $warnaStatus = match (true) {
                            $pesanan->status_pengajuan === 'Ditolak'          => 'bg-red-500/20 text-red-500',
                            $pesanan->status_pengajuan === 'Menunggu Revisi'  => 'bg-amber-500/20 text-amber-500',
                            $step === 4                                       => 'bg-emerald-500/20 text-emerald-400',
                            default                                           => 'bg-amber-500/20 text-amber-400',
                        };
                    @endphp

                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-4 sm:p-6">

                        <div class="mb-4 flex flex-col gap-3 border-b border-zinc-800 pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <h3 class="truncate text-base font-black text-white sm:text-lg">Pesanan {{ $pesanan->nomor_pengajuan }}</h3>
                                <p class="text-xs text-zinc-400">Total: <span class="font-bold text-emerald-400">Rp {{ number_format($pesanan->harga_final, 0, ',', '.') }}</span></p>
                            </div>
                            <span class="self-start shrink-0 rounded-full px-3 py-1 text-xs font-bold uppercase sm:self-auto {{ $warnaStatus }}">
                                {{ $pesanan->status_pengajuan }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Titik Reklame</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($pesanan->details as $detail)
                                    <div class="flex min-w-0 items-center gap-2 rounded-lg border border-zinc-800 bg-zinc-950 px-3 py-1.5">
                                        <span class="shrink-0 rounded border border-yellow-500/20 bg-yellow-500/10 px-2 py-0.5 font-mono text-[10px] font-bold text-yellow-500">{{ $detail->kode_titik }}</span>
                                        <span class="truncate text-xs text-zinc-300">{{ $detail->billboard?->lokasi ?? 'Data titik tidak ditemukan' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($pesanan->status_pengajuan === 'Ditolak')
                            <div class="mb-6 rounded-xl border border-red-500/20 bg-red-500/10 p-4 shadow-[0_0_15px_rgba(239,68,68,0.1)]">
                                <div class="flex items-start">
                                    <svg class="mr-3 h-6 w-6 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-black uppercase tracking-wider text-red-500">Perhatian: Pesanan Ditolak</h4>
                                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-300">
                                            Pengajuan Anda tidak dapat kami proses saat ini dengan alasan:
                                            <span class="mt-1 block break-words rounded border border-zinc-800 bg-zinc-950 p-2 font-mono text-white">"{{ $pesanan->catatan_admin ?? 'Tidak ada detail alasan yang diberikan oleh Admin.' }}"</span>
                                        </p>
                                        <p class="mt-2 text-[10px] italic text-zinc-500">*Silakan ajukan ulang pesanan baru.</p>
                                    </div>
                                </div>
                            </div>

                        @elseif($pesanan->status_pengajuan === 'Menunggu Revisi')
                            <div class="mb-6 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 shadow-[0_0_15px_rgba(245,158,11,0.1)]">
                                <div class="flex items-start">
                                    <svg class="mr-3 h-6 w-6 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <div class="w-full min-w-0">
                                        <h4 class="text-sm font-black uppercase tracking-wider text-amber-500">Tindakan Diperlukan: Revisi Dokumen</h4>
                                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-300">
                                            Titik reklame Anda <span class="font-bold text-emerald-400">Aman (Terkunci)</span>, namun admin memberikan catatan berikut:
                                            <span class="mb-3 mt-1 block break-words rounded border border-zinc-800 bg-zinc-950 p-2 font-mono text-white">"{{ $pesanan->catatan_admin }}"</span>
                                        </p>

                                        <div class="flex flex-col gap-3 border-t border-amber-500/20 pt-3 sm:flex-row sm:items-center sm:gap-4">
                                            <a href="{{ route('klien.dokumen') }}" class="flex items-center text-xs font-bold text-amber-400 transition hover:text-amber-300">
                                                1. Perbarui Dokumen di Sini &nearr;
                                            </a>
                                            <form method="POST" action="{{ route('klien.pesanan.kirim_ulang', $pesanan->id) }}" onsubmit="return confirm('Apakah Anda yakin sudah memperbaiki dokumen sesuai catatan admin? Pesanan akan diajukan ulang.')">
                                                @csrf
                                                <button type="submit" class="w-full rounded-lg bg-amber-600 px-4 py-2 text-xs font-bold text-white shadow-[0_0_15px_rgba(245,158,11,0.3)] transition hover:bg-amber-500 sm:w-auto">
                                                    2. Kirim Ulang Pesanan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            <div class="absolute bottom-2 left-[19px] top-2 w-0.5 bg-zinc-800"></div>

                            @php
                                $timeline = [
                                    [1, 'Pengajuan Diterima', 'Anda telah mengajukan sewa untuk titik ini.', 'Anda telah mengajukan sewa untuk titik ini.', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    [2, 'Skema Termin Dibuat', 'Tagihan sudah tersedia. Silakan lakukan pembayaran.', 'Menunggu kesepakatan termin.', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                    [3, 'Verifikasi Keuangan', 'Bukti bayar diterima. Sedang dicek oleh Tim Keuangan.', 'Menunggu Anda mengunggah bukti bayar.', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    [4, 'Billboard Aktif & Lunas', 'Pembayaran tervalidasi. Titik di Map sudah berubah merah!', 'Menunggu penyelesaian pembayaran.', 'M5 13l4 4L19 7'],
                                ];
                            @endphp

                            <ul class="relative z-10 space-y-6">
                                @foreach($timeline as [$urutan, $judul, $teksAktif, $teksPasif, $ikon])
                                    @php
                                        $tercapai = $urutan === 4 ? $step === 4 : $step >= $urutan;
                                        $warnaBulat = $tercapai
                                            ? ($urutan === 3 ? 'bg-amber-500 text-white' : ($urutan === 4 ? 'bg-emerald-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'bg-emerald-500 text-white'))
                                            : 'bg-zinc-800 text-zinc-500';
                                        $warnaJudul = $tercapai ? ($urutan === 4 ? 'text-emerald-400' : 'text-white') : 'text-zinc-500';
                                    @endphp
                                    <li class="flex items-start">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $warnaBulat }}">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon }}"></path></svg>
                                        </div>
                                        <div class="ml-4 mt-2 min-w-0">
                                            <h4 class="text-sm font-bold {{ $warnaJudul }}">{{ $judul }}</h4>
                                            <p class="text-xs text-zinc-500">{{ $tercapai ? $teksAktif : $teksPasif }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-10 text-center">
                        <p class="mb-2 text-zinc-400">Anda belum memiliki riwayat pesanan.</p>
                        <a href="{{ route('klien.index') }}" class="text-sm font-bold text-emerald-400 hover:underline">Cari Titik Billboard Sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-boma-layout>