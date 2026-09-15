<x-boma-layout>
    <div class="mx-auto max-w-7xl">

        <div class="mb-6 flex flex-col justify-between gap-4 md:mb-8 md:flex-row md:items-end">
            <div class="min-w-0">
                <h1 class="flex items-center gap-2 text-2xl font-black text-white sm:text-3xl">
                    Halo, {{ Auth::user()->name ?? 'Admin Utama' }} <span>👋</span>
                </h1>
                <p class="mt-1 text-sm text-zinc-400">Berikut adalah ringkasan performa Boma Advertising hari ini.</p>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:mb-8 sm:gap-6 xl:grid-cols-4">

            <div class="relative flex flex-col justify-between overflow-hidden rounded-2xl bg-red-600 p-5 text-white sm:p-6">
                <svg class="absolute -bottom-4 -right-4 h-32 w-32 text-red-700 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>

                <div class="relative z-10">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-red-200">Pengajuan Masuk</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black" data-live="admin_antrean">{{ $jumlahAntrean }}</h3>
                        <span class="text-sm font-medium text-red-200">Antrean</span>
                    </div>
                </div>

                <a href="{{ route('admin.pesanan') }}" class="relative z-10 mt-4 flex items-center text-sm font-bold transition hover:text-zinc-200">
                    Tindak Lanjuti Sekarang
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-5 sm:p-6">
                <div class="mb-2 flex items-start justify-between gap-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Okupansi Titik</p>
                    <span class="shrink-0 rounded bg-emerald-500/10 px-2 py-1 text-[10px] font-bold text-emerald-400">
                        <span data-live="admin_okupansi">{{ $persentaseOkupansi }}</span>% TERISI
                    </span>
                </div>
                <div class="mb-4 flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-white" data-live="admin_titik_terisi">{{ $titikTerisi }}</h3>
                    <span class="text-sm text-zinc-500">/ <span data-live="admin_total_titik">{{ $totalTitik }}</span> Titik</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-zinc-800">
                    <div class="h-1.5 rounded-full bg-emerald-500 transition-all duration-500"
                         data-live-lebar="admin_okupansi"
                         style="width: {{ $persentaseOkupansi }}%"></div>
                </div>
            </div>

            <div class="flex flex-col justify-between rounded-2xl border border-zinc-800 bg-zinc-900 p-5 sm:p-6">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-zinc-500">Mitra &amp; Klien Aktif</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-black text-white" data-live="admin_klien">{{ $jumlahKlien }}</h3>
                        <span class="text-sm text-zinc-500">Perusahaan</span>
                    </div>
                </div>
                <a href="{{ route('admin.klien') }}" class="mt-4 flex items-center text-xs font-bold text-red-500 transition hover:text-red-400">
                    Buka Arsip Klien
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-5 sm:p-6">
                <p class="mb-2 text-xs font-bold uppercase tracking-wider text-zinc-500">Proyeksi Pendapatan (Bln Ini)</p>
                <h3 class="break-all text-xl font-black text-white sm:text-2xl">
                    Rp <span data-live="admin_proyeksi">{{ number_format($proyeksiPendapatan, 0, ',', '.') }}</span>
                </h3>
                <div class="mt-4 flex h-8 items-end gap-1">
                    <div class="h-1/3 w-1/6 rounded-t-sm bg-zinc-800"></div>
                    <div class="h-1/2 w-1/6 rounded-t-sm bg-zinc-800"></div>
                    <div class="h-2/3 w-1/6 rounded-t-sm bg-zinc-800"></div>
                    <div class="h-1/3 w-1/6 rounded-t-sm bg-zinc-800"></div>
                    <div class="h-4/5 w-1/6 rounded-t-sm bg-zinc-800"></div>
                    <div class="h-full w-1/6 rounded-t-sm bg-red-600"></div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900">

            <div class="flex flex-col gap-3 border-b border-zinc-800 bg-zinc-900/50 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div class="flex items-center">
                    <span class="mr-3 h-3 w-3 shrink-0 animate-pulse rounded-full bg-red-500"></span>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-white">Pengajuan Sewa Masuk (Menunggu ACC)</h3>
                        <p class="mt-1 text-xs text-zinc-500">Daftar klien yang baru saja mengirimkan form melalui halaman keranjang.</p>
                    </div>
                </div>
                <a href="{{ route('admin.pesanan') }}" class="shrink-0 text-xs font-medium text-zinc-400 transition hover:text-white">Lihat Semua Riwayat</a>
            </div>

            {{-- Tampilan kartu untuk layar kecil --}}
            <div class="divide-y divide-zinc-800/50 md:hidden">
                @forelse($antreanPengajuan as $pengajuan)
                    @php
                        $mulai   = \Carbon\Carbon::parse($pengajuan->mulai_sewa);
                        $selesai = \Carbon\Carbon::parse($pengajuan->selesai_sewa);
                        $durasi  = max(1, (int) $mulai->diffInMonths($selesai));
                    @endphp
                    <div class="space-y-3 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-white">{{ $pengajuan->user->name ?? 'Tidak Diketahui' }}</p>
                                <p class="mt-0.5 truncate text-xs text-zinc-500">{{ $pengajuan->user->nama_perusahaan ?? 'Perorangan' }}</p>
                            </div>
                            <span class="shrink-0 whitespace-nowrap text-xs text-zinc-400">{{ $pengajuan->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex flex-wrap gap-1">
                            @forelse($pengajuan->details as $detail)
                                <span class="rounded-md border border-zinc-700 bg-zinc-800 px-2 py-1 text-[10px] font-bold text-zinc-300">{{ $detail->kode_titik }}</span>
                            @empty
                                <span class="text-xs text-zinc-500">-</span>
                            @endforelse
                        </div>

                        <div class="flex items-center justify-between gap-2 text-xs">
                            <span class="text-zinc-400">{{ $durasi }} Bulan</span>
                            <span class="font-bold text-white">Rp {{ number_format($pengajuan->estimasi_harga, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('admin.pesanan') }}" class="block rounded-lg border border-red-500/20 bg-red-600/10 px-4 py-2.5 text-center text-xs font-bold text-red-500 transition hover:bg-red-600 hover:text-white">
                            Tinjau &amp; ACC
                        </a>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center p-12 text-center">
                        <svg class="mb-3 h-12 w-12 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm text-zinc-500">Belum ada pengajuan sewa baru yang menunggu ACC.</p>
                    </div>
                @endforelse
            </div>

            {{-- Tampilan tabel untuk layar besar --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-zinc-800 bg-zinc-950/50 text-[10px] font-bold uppercase tracking-wider text-zinc-500">
                            <th scope="col" class="p-4 pl-6">Tgl Masuk</th>
                            <th scope="col" class="p-4">Nama Klien / Instansi</th>
                            <th scope="col" class="p-4">Kode Titik Diminta</th>
                            <th scope="col" class="p-4">Durasi</th>
                            <th scope="col" class="p-4">Estimasi Total</th>
                            <th scope="col" class="p-4 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50 text-sm">
                        @forelse($antreanPengajuan as $pengajuan)
                            @php
                                $mulai   = \Carbon\Carbon::parse($pengajuan->mulai_sewa);
                                $selesai = \Carbon\Carbon::parse($pengajuan->selesai_sewa);
                                $durasi  = max(1, (int) $mulai->diffInMonths($selesai));
                            @endphp
                            <tr class="transition hover:bg-zinc-800/30">
                                <td class="whitespace-nowrap p-4 pl-6 text-xs text-zinc-400">{{ $pengajuan->created_at->diffForHumans() }}</td>
                                <td class="p-4">
                                    <p class="text-sm font-bold text-white">{{ $pengajuan->user->name ?? 'Tidak Diketahui' }}</p>
                                    <p class="mt-0.5 text-xs text-zinc-500">{{ $pengajuan->user->nama_perusahaan ?? 'Perorangan' }}</p>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($pengajuan->details as $detail)
                                            <span class="rounded-md border border-zinc-700 bg-zinc-800 px-2 py-1 text-[10px] font-bold text-zinc-300">{{ $detail->kode_titik }}</span>
                                        @empty
                                            <span class="text-xs text-zinc-500">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="p-4 text-xs font-medium text-zinc-300">{{ $durasi }} Bulan</td>
                                <td class="whitespace-nowrap p-4 font-bold text-white">Rp {{ number_format($pengajuan->estimasi_harga, 0, ',', '.') }}</td>
                                <td class="p-4 pr-6 text-right">
                                    <a href="{{ route('admin.pesanan') }}" class="inline-block rounded-lg border border-red-500/20 bg-red-600/10 px-4 py-2 text-xs font-bold text-red-500 transition hover:bg-red-600 hover:text-white">
                                        Tinjau &amp; ACC
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="mb-3 h-12 w-12 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-sm text-zinc-500">Belum ada pengajuan sewa baru yang menunggu ACC.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-boma-layout>