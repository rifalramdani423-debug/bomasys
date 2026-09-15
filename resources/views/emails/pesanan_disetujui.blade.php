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
        .footer { margin-top: 30px; font-size: 12px; color: #a1a1aa; text-align: center; border-top: 1px solid #e4e4e7; padding-top: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Pengajuan Sewa Disetujui! 🎉</div>
        <div class="content">
            <p>Halo, <strong>{{ $pengajuan->user->name }}</strong>,</p>
            <p>Kabar baik! Pengajuan sewa titik reklame Anda telah <strong>DISETUJUI (ACC)</strong> oleh tim BOMA Advertising. Berikut adalah rincian lengkap pesanan Anda:</p>
            
            <div class="box">
                <div class="box-title">Rincian Administratif</div>
                <div class="info-row"><span class="info-label">No. Pengajuan</span>: <strong>{{ $pengajuan->nomor_pengajuan }}</strong></div>
                <div class="info-row"><span class="info-label">Waktu Pengajuan</span>: {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d F Y - H:i:s') }} WIB</div>
                <div class="info-row"><span class="info-label">Masa Sewa</span>: {{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}</div>
                
                <div class="box-title" style="margin-top: 25px;">Daftar Titik Lokasi</div>
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

            <p><strong>Langkah Selanjutnya:</strong><br>
            Tim kami akan segera menghubungi Anda via WhatsApp untuk mendiskusikan <strong>Harga Final Kesepakatan</strong>. Setelah harga disetujui, kami akan menerbitkan tagihan resmi ke Dashboard BOMA SYS Anda, dan Anda dapat mulai mengatur skema cicilan atau pelunasan (Termin).</p>
            
            <p>Terima kasih atas kepercayaan Anda!<br><br>Salam Hangat,<br><strong>Tim BOMA Advertising</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA SYS. Email ini dihasilkan otomatis oleh sistem, mohon untuk tidak membalas email ini.
        </div>
    </div>
</body>
</html>