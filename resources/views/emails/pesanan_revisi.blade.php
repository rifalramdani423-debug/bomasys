<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revisi Dokumen Sewa</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f5; margin: 0; padding: 0; color: #3f3f46; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #18181b; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .header h1 span { color: #dc2626; }
        .content { padding: 30px; line-height: 1.6; }
        .alert-box { background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px 20px; border-radius: 4px; margin: 20px 0; }
        .alert-title { color: #b45309; font-weight: bold; margin-top: 0; margin-bottom: 5px; font-size: 14px; text-transform: uppercase; }
        .reason-text { font-family: monospace; background-color: #ffffff; border: 1px solid #fde68a; padding: 10px; border-radius: 4px; color: #92400e; display: block; margin-top: 10px; }
        .btn { display: inline-block; background-color: #f59e0b; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { background-color: #f4f4f5; padding: 20px; text-align: center; font-size: 12px; color: #71717a; border-top: 1px solid #e4e4e7; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BOMA <span>SYS</span></h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $pengajuan->user->name ?? 'Klien BOMA' }}</strong>,</p>
            <p>Terima kasih telah mengajukan penyewaan di BOMA Advertising. Pengajuan Anda dengan nomor referensi <strong>{{ $pengajuan->nomor_pengajuan }}</strong> masih <strong>AMAN (Terkunci)</strong>, namun Admin kami memerlukan sedikit revisi dokumen sebelum melanjutkan proses.</p>

            <div style="background-color: #fafafa; border: 1px solid #e4e4e7; border-radius: 4px; padding: 12px 16px; margin: 16px 0; font-size: 13px;">
                <p style="margin: 0 0 6px 0; font-weight: bold; color: #3f3f46;">Titik Reklame yang Diamankan:</p>
                @foreach($pengajuan->details as $detail)
                    <p style="margin: 2px 0;">&bull; <strong>{{ $detail->kode_titik }}</strong> &mdash; {{ $detail->billboard?->lokasi ?? 'Data lokasi tidak ditemukan' }}</p>
                @endforeach
            </div>

            <div class="alert-box">
                <p class="alert-title">Catatan Revisi dari Admin:</p>
                <span class="reason-text">"{{ $pengajuan->catatan_admin }}"</span>
            </div>

            <p>Silakan masuk ke Dashboard Anda, hapus dokumen yang salah di menu <strong>Arsip Data Klien</strong>, unggah dokumen yang baru, lalu klik tombol <strong>Kirim Ulang Pesanan</strong>.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Perbaiki Dokumen Sekarang</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA Advertising.
        </div>
    </div>
</body>
</html>