Halo, {{ $pengajuan->user->name ?? 'Klien BOMA' }},

Terima kasih telah melakukan pengajuan sewa titik reklame di BOMA Advertising. Mohon maaf, setelah kami melakukan verifikasi, pengajuan Anda dengan nomor referensi {{ $pengajuan->nomor_pengajuan }} tidak dapat kami proses lebih lanjut.

Titik Reklame yang Diajukan:
@foreach($pengajuan->details as $detail)
- {{ $detail->kode_titik }} ({{ $detail->billboard?->lokasi ?? 'Data lokasi tidak ditemukan' }})
@endforeach

Alasan Penolakan:
"{{ $pengajuan->catatan_admin }}"

Untuk melanjutkan penyewaan, silakan lakukan perbaikan sesuai catatan di atas dan buat pengajuan ulang melalui Dashboard Klien Anda.

Masuk ke Dashboard: {{ url('/') }}

Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim Admin kami via WhatsApp.

--
BOMA Advertising. Hak Cipta Dilindungi.
