KESEPAKATAN HARGA FINAL 🤝

Halo, {{ $pengajuan->user->name }},

Berdasarkan diskusi yang telah dilakukan, Tim BOMA Advertising telah menerbitkan Harga Final untuk pesanan sewa reklame Anda:

No. Pengajuan : {{ $pengajuan->nomor_pengajuan }}
Masa Sewa     : {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}
TOTAL DEAL    : Rp {{ number_format($pengajuan->harga_final, 0, ',', '.') }}
(Total sudah termasuk pajak dan biaya layanan yang disepakati)

=========================================
TINDAKAN DIPERLUKAN SEGERA!
=========================================
Silakan masuk ke Dashboard Anda sekarang juga untuk MENGISI TERMIN PEMBAYARAN (Skema Cicilan).

CATATAN PENTING: 
Setelah Anda selesai membuat termin di sistem, Nomor Virtual Account (VA) Midtrans akan otomatis dikirimkan ke email Anda ini untuk mempermudah proses transfer.

Atur termin Anda melalui tautan berikut:
{{ url('/klien/keuangan') }}

Terima kasih atas kerja sama Anda!

Salam Hangat,
Tim Keuangan BOMA
--
BOMA SYS. Email ini dihasilkan otomatis oleh sistem.