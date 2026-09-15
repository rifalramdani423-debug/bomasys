<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pesanan Baru Masuk</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #18181b; padding: 30px; color: #e4e4e7;">
    <div style="background-color: #27272a; padding: 30px; border-radius: 12px; max-width: 600px; margin: 0 auto; border: 1px solid #3f3f46;">
        <h2 style="color: #10b981; margin-top: 0;">🚀 Klien Baru Siap Tayang!</h2>
        <p>Halo Tim Aset,</p>
        <p>Divisi Keuangan baru saja memvalidasi pelunasan Termin 1. Klien sudah masuk ke tahap produksi dan titik reklame telah dikunci (Disewa).</p>
        
        <div style="background-color: #18181b; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10b981;">
            <ul style="list-style: none; padding: 0; margin: 0; line-height: 1.8;">
                <li><strong>No. Pesanan:</strong> {{ $pengajuan->nomor_pengajuan ?? $pengajuan->id }}</li>
                <li><strong>Klien / Perusahaan:</strong> {{ $pengajuan->user->nama_perusahaan ?? $pengajuan->user->name }}</li>
                <li><strong>Titik Dipesan:</strong> {{ $pengajuan->details->count() }} Lokasi Reklame</li>
            </ul>
        </div>

        <p>Silakan segera cek dashboard <strong>BOMA SYS (Divisi Aset)</strong> Anda untuk menjadwalkan pemasangan visual (IM) dan berkoordinasi dengan Tim Produksi.</p>
        <br>
        <hr style="border: 0; border-top: 1px solid #3f3f46; margin: 20px 0;">
        <p style="font-size: 12px; color: #a1a1aa;">Email ini dikirim otomatis oleh BOMA SYS. Mohon tidak membalas email ini.</p>
    </div>
</body>
</html>