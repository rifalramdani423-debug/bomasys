<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-w-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 5px solid #dc2626; }
        .header { font-size: 20px; font-weight: bold; color: #18181b; margin-bottom: 20px; border-bottom: 1px solid #e4e4e7; padding-bottom: 10px; }
        .content { font-size: 14px; color: #3f3f46; line-height: 1.6; }
        .data-table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 10px; text-align: left; border-bottom: 1px solid #e4e4e7; }
        .data-table th { width: 40%; color: #71717a; font-weight: normal; }
        .data-table td { font-weight: bold; color: #18181b; }
        .footer { margin-top: 30px; font-size: 12px; color: #a1a1aa; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Notifikasi Klien Baru Terdaftar
        </div>
        <div class="content">
            <p>Halo Admin,</p>
            <p>Sistem BOMA Sys baru saja menerima pendaftaran akun klien baru. Berikut adalah rincian datanya:</p>
            
            <table class="data-table">
                <tr>
                    <th>Perusahaan / Instansi</th>
                    <td>{{ $klien->nama_perusahaan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama PIC</th>
                    <td>{{ $klien->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $klien->email }}</td>
                </tr>
                <tr>
                    <th>Nomor WhatsApp</th>
                    <td>{{ $klien->no_wa ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Waktu Pendaftaran</th>
                    <td>{{ $klien->created_at->format('d F Y, H:i') }} WIB</td>
                </tr>
            </table>

            <p style="margin-top: 20px;">Silakan periksa di dashboard Admin untuk meninjau aktivitas klien ini.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA Advertising System. Email otomatis oleh sistem.
        </div>
    </div>
</body>
</html>