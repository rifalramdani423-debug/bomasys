<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 5px solid #dc2626; }
        .header { font-size: 20px; font-weight: bold; color: #18181b; margin-bottom: 20px; border-bottom: 1px solid #e4e4e7; padding-bottom: 10px; }
        .content { font-size: 14px; color: #3f3f46; line-height: 1.6; }
        .highlight { font-weight: bold; color: #dc2626; }
        .btn { display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #dc2626; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #a1a1aa; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Pendaftaran Berhasil 🎉
        </div>
        <div class="content">
            <p>Halo <span class="highlight">{{ $klien->name }}</span>,</p>
            <p>Selamat! Akun Anda telah berhasil terdaftar di sistem penyewaan titik iklan <strong>BOMA Advertising</strong>.</p>
            
            <p>Kini Anda dapat mulai mencari, mengecek ketersediaan, dan memesan titik reklame strategis yang kami sediakan secara langsung melalui sistem.</p>
            
            <p>Pastikan Anda segera melengkapi dokumen persyaratan (KTP dan NPWP) pada menu profil Anda untuk mempercepat proses persetujuan pesanan nantinya.</p>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Masuk ke Dashboard</a>
            </div>
            
            <p style="margin-top: 20px;">Terima kasih atas kepercayaannya.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA Advertising. Harap jangan membalas email otomatis ini.
        </div>
    </div>
</body>
</html>