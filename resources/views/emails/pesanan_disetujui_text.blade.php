Halo, {{ $pengajuan->user->name }}! 🎉

Kabar baik! Pengajuan sewa titik reklame Anda telah DISETUJUI (ACC) oleh tim Admin BOMA Advertising. Berikut adalah rincian lengkap pesanan Anda:

=========================================
RINCIAN PENGAJUAN
=========================================
No. Pengajuan   : {{ $pengajuan->nomor_pengajuan }}
Waktu Pengajuan : {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d F Y - H:i:s') }} WIB
Masa Sewa       : {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}

DAFTAR LOKASI REKLAME:
@foreach($pengajuan->details as $index => $detail)
{{ $index + 1 }}. KODE TITIK: {{ $detail->kode_titik }}
   📍 Alamat: {{ $detail->billboard?->lokasi ?? 'Alamat tidak ditemukan' }}
   (Kota: {{ $detail->billboard?->kab_kota ?? '-' }} | Jenis: {{ $detail->billboard?->jenis_ooh ?? '-' }} | Ukuran: {{ $detail->billboard?->ukuran ?? '-' }})
@endforeach
=========================================

LANGKAH SELANJUTNYA:
Tim kami akan segera menghubungi Anda via WhatsApp untuk mendiskusikan Harga Final Kesepakatan. Setelah harga disetujui, sistem kami akan menerbitkan tagihan resmi ke Dashboard Anda, dan Anda dapat mulai mengatur skema pembayaran (Termin).

Terima kasih atas kepercayaan Anda!

Salam Hangat,
Tim BOMA Advertising

--
BOMA SYS. Email ini dihasilkan otomatis oleh sistem.