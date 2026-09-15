<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f5; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; border-top: 5px solid #dc2626; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { font-size: 22px; font-weight: 900; color: #18181b; margin-bottom: 20px; text-align: center;}
        .content { font-size: 14px; color: #3f3f46; line-height: 1.6; }
        .box { background-color: #fafafa; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e4e4e7; }
        .box-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #71717a; font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #e4e4e7; padding-bottom: 8px;}
        .info-row { margin-bottom: 8px; }
        .info-label { font-weight: bold; color: #18181b; display: inline-block; width: 140px; }
        .titik-list { margin-top: 15px; padding-left: 0; list-style-type: none; }
        .titik-item { background: #ffffff; border: 1px solid #e4e4e7; padding: 15px; margin-bottom: 10px; border-radius: 8px; border-left: 4px solid #dc2626; }
        .titik-kode { font-weight: 900; color: #dc2626; font-size: 15px; margin-bottom: 4px; }
        .titik-alamat { font-size: 13px; color: #27272a; margin: 4px 0; font-weight: bold; }
        .titik-detail { font-size: 11px; color: #71717a; margin-top: 6px; padding-top: 6px; border-top: 1px dashed #e4e4e7; }
        .btn-wrapper { text-align: center; margin-top: 25px; }
        .btn { display: inline-block; padding: 14px 28px; background-color: #dc2626; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; letter-spacing: 0.5px; }
        .footer { margin-top: 30px; font-size: 12px; color: #a1a1aa; text-align: center; border-top: 1px solid #e4e4e7; padding-top: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Pesanan Baru Masuk! 📥</div>
        <div class="content">
            <p>Halo <strong>Tim Admin BOMA</strong>,</p>
            <p>Terdapat satu pengajuan sewa reklame baru yang masuk ke sistem dan menunggu verifikasi Anda. Berikut adalah rincian lengkap pesanan tersebut:</p>
            
            <div class="box">
                <div class="box-title">Identitas Klien</div>
                <div class="info-row"><span class="info-label">Nama PIC</span>: <strong>{{ $pengajuan->user->name }}</strong></div>
                <div class="info-row"><span class="info-label">Instansi/Klien</span>: {{ $pengajuan->user->nama_perusahaan ?? '-' }}</div>
                <div class="info-row"><span class="info-label">WhatsApp</span>: {{ $pengajuan->user->no_wa ?? 'Tidak dicantumkan' }}</div>
                <div class="info-row"><span class="info-label">Email</span>: {{ $pengajuan->user->email ?? '-' }}</div>
            </div>

            <div class="box">
                <div class="box-title">Rincian Pengajuan</div>
                <div class="info-row"><span class="info-label">No. Pengajuan</span>: <strong>{{ $pengajuan->nomor_pengajuan ?? '-' }}</strong></div>
                <div class="info-row"><span class="info-label">Waktu Pengajuan</span>: {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d F Y - H:i:s') }} WIB</div>
                <div class="info-row"><span class="info-label">Jadwal Sewa</span>: {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}</div>
                <div class="info-row"><span class="info-label">Materi Visual</span>: 
                    @if($pengajuan->jasa_desain)
                        <span style="color: #d97706; font-weight: bold;">Meminta Jasa Desain BOMA</span>
                    @else
                        <span style="color: #059669; font-weight: bold;">Membawa Materi Sendiri</span>
                    @endif
                </div>
                @if(!$pengajuan->jasa_desain && $pengajuan->link_desain)
                    <div class="info-row"><span class="info-label">Link Mentahan</span>: <a href="{{ $pengajuan->link_desain }}" target="_blank" style="color: #2563eb;">Buka Link Klien</a></div>
                @endif
                
                <div class="box-title" style="margin-top: 25px;">Daftar Titik Diminta</div>
                <ul class="titik-list">
                    @foreach($pengajuan->details as $detail)
                    <li class="titik-item">
                        <div class="titik-kode">{{ $detail->kode_titik }}</div>
                        <div class="titik-alamat">📍 {{ $detail->billboard?->lokasi ?? 'Alamat lengkap tidak ditemukan' }}</div>
                        <div class="titik-detail">
                            <strong>Kota/Kab:</strong> {{ $detail->billboard?->kab_kota ?? '-' }} &nbsp;|&nbsp; 
                            <strong>Jenis OOH:</strong> {{ $detail->billboard?->jenis_ooh ?? '-' }} &nbsp;|&nbsp; 
                            <strong>Ukuran:</strong> {{ $detail->billboard?->ukuran ?? '-' }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/admin/pesanan') }}" class="btn">Tinjau di Dashboard Admin &rarr;</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA SYS. Notifikasi otomatis sistem internal.
        </div>
    </div>
</body>
</html>