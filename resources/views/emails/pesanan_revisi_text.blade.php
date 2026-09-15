Halo, {{ $pengajuan->user->name ?? 'Klien BOMA' }},

Terima kasih telah mengajukan penyewaan di BOMA Advertising. Pengajuan Anda dengan nomor referensi {{ $pengajuan->nomor_pengajuan }} masih AMAN (Terkunci), namun Admin kami memerlukan sedikit revisi dokumen sebelum melanjutkan proses.

Titik Reklame yang Diamankan:
@foreach($pengajuan->details as $detail)
- {{ $detail->kode_titik }} ({{ $detail->billboard?->lokasi ?? 'Data lokasi tidak ditemukan' }})
@endforeach

Catatan Revisi dari Admin:
"{{ $pengajuan->catatan_admin }}"

Silakan masuk ke Dashboard Anda, hapus dokumen yang salah di menu Arsip Data Klien, unggah dokumen yang baru, lalu klik tombol Kirim Ulang Pesanan.

Perbaiki Dokumen Sekarang: {{ url('/') }}

--
BOMA Advertising.
