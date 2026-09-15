<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f5; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; border-top: 5px solid #10b981; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { font-size: 22px; font-weight: 900; color: #18181b; margin-bottom: 20px; text-align: center;}
        .content { font-size: 14px; color: #3f3f46; line-height: 1.6; }
        .box { background-color: #fafafa; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e4e4e7; }
        .info-row { margin-bottom: 8px; }
        .info-label { font-weight: bold; color: #71717a; display: inline-block; width: 140px; }
        .price-highlight { font-size: 24px; font-weight: 900; color: #10b981; margin: 15px 0; padding: 15px 0; border-top: 2px dashed #e4e4e7; border-bottom: 2px dashed #e4e4e7; text-align: center; }
        .alert-box { background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px; margin-top: 25px; }
        .btn-wrapper { text-align: center; margin-top: 30px; margin-bottom: 15px; }
        .btn { display: inline-block; padding: 14px 28px; background-color: #10b981; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; letter-spacing: 0.5px; }
        .footer { margin-top: 30px; font-size: 12px; color: #a1a1aa; text-align: center; border-top: 1px solid #e4e4e7; padding-top: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Kesepakatan Harga Final 🤝</div>
        <div class="content">
            <p>Halo, <strong>{{ $pengajuan->user->name }}</strong>,</p>
            <p>Berdasarkan diskusi dan penyesuaian yang telah dilakukan, Tim BOMA Advertising telah menerbitkan rincian <strong>Harga Final</strong> untuk pesanan sewa reklame Anda.</p>
            
            <div class="box">
                <div class="info-row"><span class="info-label">No. Pengajuan</span>: <strong style="color: #18181b;">{{ $pengajuan->nomor_pengajuan }}</strong></div>
                <div class="info-row"><span class="info-label">Masa Sewa</span>: <strong style="color: #18181b;">{{ \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->selesai_sewa)->format('d M Y') }}</strong></div>
                
                <div class="price-highlight">
                    Rp {{ number_format($pengajuan->harga_final, 0, ',', '.') }}
                </div>
                <div style="text-align: center; font-size: 12px; color: #71717a;">Total sudah termasuk pajak dan biaya layanan yang disepakati.</div>
            </div>

            <div class="alert-box">
                <strong style="color: #1e3a8a;">Tindakan Diperlukan Segera:</strong><br>
                Silakan masuk ke Dashboard Anda sekarang juga untuk <strong>mengisi skema Termin Pembayaran (Cicilan)</strong>. <br><br>
                <em>Catatan: Setelah Anda selesai membuat termin, tagihan akan terbentuk dan <strong>Nomor Virtual Account (VA) Midtrans akan otomatis dikirimkan ke email Anda ini</strong> untuk mempermudah proses pembayaran.</em>
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/klien/keuangan') }}" class="btn">Atur Termin Pembayaran Sekarang</a>
            </div>
            
            <p>Terima kasih atas kerja sama Anda yang luar biasa!<br><br>Salam Hangat,<br><strong>Tim Keuangan BOMA</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA SYS. Email ini dihasilkan otomatis oleh sistem, mohon untuk tidak membalas email ini.
        </div>
    </div>
</body>
</html>