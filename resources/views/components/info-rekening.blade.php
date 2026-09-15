{{--
    Komponen: Informasi Rekening Tujuan Pembayaran
    Lokasi   : resources/views/components/info-rekening.blade.php

    Cara pakai di view mana pun:
        <x-info-rekening />

    Untuk versi ringkas (tanpa catatan keamanan dan tanpa kontak):
        <x-info-rekening :ringkas="true" />

    Sumber data: config/boma.php
--}}

@props(['ringkas' => false])

@php
    $daftarRekening = config('boma.rekening', []);
    $waKeuangan     = config('boma.wa_keuangan');
@endphp

<div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 sm:p-5">

    <div class="flex items-center gap-2 mb-1">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        <h3 class="text-sm font-bold text-white">Rekening Tujuan Pembayaran</h3>
    </div>
    <p class="text-[10px] text-zinc-500 mb-4">
        Silakan lakukan transfer ke salah satu rekening resmi di bawah ini, lalu unggah bukti pembayarannya.
    </p>

    @forelse ($daftarRekening as $rek)
        <div x-data="{ tersalin: false }"
             class="bg-zinc-950 border border-zinc-800 rounded-xl p-3.5 mb-3 last:mb-0 hover:border-zinc-700 transition">

            <div class="flex items-start justify-between gap-3 mb-2.5">
                <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ $rek['bank'] }}</p>
                    @if (!empty($rek['cabang']))
                        <p class="text-[10px] text-zinc-500 truncate">{{ $rek['cabang'] }}</p>
                    @endif
                </div>
                <span class="text-[9px] font-black tracking-wider text-zinc-400 bg-zinc-800 border border-zinc-700 px-2 py-1 rounded shrink-0">
                    {{ $rek['kode'] ?? 'BANK' }}
                </span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <p class="flex-1 text-lg sm:text-xl font-black font-mono text-emerald-400 tracking-wider break-all">
                    {{ $rek['nomor'] }}
                </p>

                <button type="button"
                        @click="
                            navigator.clipboard.writeText('{{ preg_replace('/[^0-9]/', '', $rek['nomor']) }}');
                            tersalin = true;
                            setTimeout(() => tersalin = false, 2000);
                        "
                        class="shrink-0 w-full sm:w-auto px-3 py-1.5 rounded-lg text-[10px] font-bold border transition flex items-center justify-center gap-1.5"
                        :class="tersalin
                            ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/40'
                            : 'bg-zinc-800 hover:bg-zinc-700 text-zinc-300 border-zinc-700'">
                    <svg x-show="!tersalin" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <svg x-show="tersalin" x-cloak class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span x-text="tersalin ? 'Tersalin!' : 'Salin Nomor'"></span>
                </button>
            </div>

            <div class="mt-2.5 pt-2.5 border-t border-zinc-800">
                <p class="text-[9px] text-zinc-500 uppercase tracking-wide">Atas Nama</p>
                <p class="text-xs font-bold text-zinc-200">{{ $rek['atas_nama'] }}</p>
            </div>
        </div>
    @empty
        <div class="bg-zinc-950 border border-dashed border-zinc-700 rounded-xl p-4 text-center">
            <p class="text-xs text-zinc-500">Data rekening belum dikonfigurasi.</p>
            <p class="text-[10px] text-zinc-600 mt-1">Hubungi administrator sistem.</p>
        </div>
    @endforelse

    @unless ($ringkas)
        {{-- Catatan keamanan: mencegah penipuan rekening palsu --}}
        <div class="mt-4 bg-orange-500/10 border border-orange-500/25 rounded-xl p-3">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-orange-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.71-3L13.71 4a2 2 0 00-3.42 0L3.36 16a2 2 0 001.71 3z"></path>
                </svg>
                <div class="text-[10px] text-orange-300 leading-relaxed space-y-1">
                    <p class="font-bold">Perhatian sebelum melakukan transfer</p>
                    <p>Pastikan nama penerima adalah <b>{{ $daftarRekening[0]['atas_nama'] ?? 'PT BOMA Advertising' }}</b>. BOMA Advertising tidak pernah meminta pembayaran ke rekening atas nama perorangan.</p>
                    <p>Transfer sesuai nominal tagihan agar proses verifikasi tidak tertunda.</p>
                </div>
            </div>
        </div>

        @if ($waKeuangan)
            <a href="https://wa.me/{{ $waKeuangan }}" target="_blank"
               class="mt-3 w-full bg-zinc-800 hover:bg-zinc-700 text-zinc-300 border border-zinc-700 px-3 py-2.5 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Konfirmasi ke {{ config('boma.nama_keuangan', 'Bagian Keuangan') }}
            </a>
        @endif
    @endunless

</div>