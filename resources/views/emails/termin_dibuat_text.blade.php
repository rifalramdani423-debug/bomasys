SKEMA TERMIN BERHASIL DIBUAT ✅

Halo, {{ $pesanan->user->name }},

Skema pembayaran (Termin) untuk pesanan sewa reklame Anda telah berhasil disimpan ke dalam sistem. Berikut rinciannya:

No. Pengajuan : {{ $pesanan->nomor_pengajuan }}
Total Tagihan : Rp {{ number_format($pesanan->harga_final, 0, ',', '.') }}
Total Cicilan : {{ $pesanan->termins->count() }} Kali Pembayaran

=========================================
RINCIAN TAGIHAN & VIRTUAL ACCOUNT
=========================================
@foreach($pesanan->termins as $termin)

TERMIN #{{ $termin->termin_ke }} ({{ $termin->persentase }}%)
Nominal     : Rp {{ number_format($termin->nominal, 0, ',', '.') }}
Jatuh Tempo : {{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d F Y') }}
@if($termin->nomor_va)
VA MANDIRI  : {{ $termin->nomor_va }}
@else
(VA akan diterbitkan setelah termin sebelumnya Lunas)
@endif
-----------------------------------------
@endforeach

Harap lakukan pembayaran sebelum tanggal jatuh tempo.

Cek Dashboard Anda di sini:
{{ url('/klien/keuangan') }}

--
BOMA SYS. Email ini dihasilkan otomatis oleh sistem.