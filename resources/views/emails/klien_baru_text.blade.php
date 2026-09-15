NOTIFIKASI KLIEN BARU TERDAFTAR

Halo Admin,

Sistem BOMA Sys baru saja menerima pendaftaran akun klien baru. Berikut adalah rincian datanya:

Perusahaan / Instansi : {{ $klien->nama_perusahaan ?? '-' }}
Nama PIC              : {{ $klien->name }}
Email                 : {{ $klien->email }}
Nomor WhatsApp        : {{ $klien->no_wa ?? '-' }}
Waktu Pendaftaran     : {{ $klien->created_at->format('d F Y, H:i') }} WIB

Silakan periksa di dashboard Admin untuk meninjau aktivitas klien ini.

--
BOMA Advertising System. Email otomatis oleh sistem.
