PESANAN BARU MASUK! 📥

Halo Tim Admin BOMA,

Terdapat satu pengajuan sewa reklame baru yang masuk ke sistem dan menunggu verifikasi Anda. Berikut adalah rincian lengkapnya:

=========================================
IDENTITAS KLIEN
=========================================
Nama PIC      : {{ $pengajuan->user->name }}
Instansi      : {{ $pengajuan->user->nama_perusahaan ?? '-' }}
WhatsApp      : {{ $pengajuan->user->no_wa ?? 'Tidak dicantumkan' }}
Email         : {{ $pengajuan->user->email ?? '-' }}

=========================================
RINCIAN PENGAJUAN
=========================================
No. Pengajuan : {{ $pengajuan->nomor_pengajuan ?? '-' }}
Waktu Masuk   : {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d F Y - H:i:s') }} WIB
Jadwal Sewa   : {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}
Materi Visual : {{ $pengajuan->jasa_desain ? 'Meminta Jasa Desain BOMA' : 'Membawa Materi Sendiri' }}
@if(!$pengajuan->jasa_desain && $pengajuan->link_desain)
Link Mentahan : {{ $pengajuan->link_desain }}
@endif

DAFTAR TITIK DIMINTA:
@foreach($pengajuan->details as $index => $detail)
{{ $index + 1 }}. KODE TITIK: {{ $detail->kode_titik }}
   📍 Alamat: {{ $detail->billboard?->lokasi ?? 'Alamat tidak ditemukan' }}
   (Kota: {{ $detail->billboard?->kab_kota ?? '-' }} | Jenis: {{ $detail->billboard?->jenis_ooh ?? '-' }} | Ukuran: {{ $detail->billboard?->ukuran ?? '-' }})
@endforeach
=========================================

Tinjau dan proses pesanan ini segera melalui Dashboard Admin BOMA SYS:
{{ url('/admin/pesanan') }}

--
BOMA SYS. Notifikasi otomatis sistem internal.