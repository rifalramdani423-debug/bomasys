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
        .termin-list { margin-top: 15px; padding-left: 0; list-style-type: none; }
        .termin-item { background: #ffffff; border: 1px solid #e4e4e7; padding: 15px; margin-bottom: 15px; border-radius: 8px; border-left: 4px solid #10b981; }
        .termin-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #e4e4e7; padding-bottom: 10px; margin-bottom: 10px; }
        .termin-title { font-weight: 900; color: #18181b; font-size: 15px; }
        .termin-price { font-weight: 900; color: #10b981; font-size: 16px; }
        .termin-detail { font-size: 13px; color: #3f3f46; margin-bottom: 4px; }
        .va-box { background-color: #18181b; color: #10b981; padding: 12px; border-radius: 6px; font-family: monospace; font-size: 18px; font-weight: bold; text-align: center; margin-top: 15px; letter-spacing: 2px; }
        .va-label { font-size: 10px; color: #a1a1aa; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px; }
        .btn-wrapper { text-align: center; margin-top: 30px; }
        .btn { display: inline-block; padding: 14px 28px; background-color: #18181b; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; letter-spacing: 0.5px; }
        .footer { margin-top: 30px; font-size: 12px; color: #a1a1aa; text-align: center; border-top: 1px solid #e4e4e7; padding-top: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Skema Termin Berhasil Dibuat ✅</div>
        <div class="content">
            <p>Halo, <strong>{{ $pesanan->user->name }}</strong>,</p>
            <p>Terima kasih! Skema pembayaran (Termin) untuk pesanan sewa reklame Anda telah berhasil disimpan ke dalam sistem. Berikut adalah rincian tagihan beserta Nomor Virtual Account (VA) Anda:</p>
            
            <div class="box">
                <div class="info-row"><span class="info-label">No. Pengajuan</span>: <strong style="color: #18181b;">{{ $pesanan->nomor_pengajuan }}</strong></div>
                <div class="info-row"><span class="info-label">Total Keseluruhan</span>: <strong style="color: #18181b;">Rp {{ number_format($pesanan->harga_final, 0, ',', '.') }}</strong></div>
                <div class="info-row"><span class="info-label">Jumlah Cicilan</span>: <strong style="color: #18181b;">{{ $pesanan->termins->count() }} Kali Pembayaran</strong></div>
            </div>

            <ul class="termin-list">
                @foreach($pesanan->termins as $termin)
                <li class="termin-item">
                    <div class="termin-header">
                        <span class="termin-title">Termin #{{ $termin->termin_ke }} ({{ $termin->persentase }}%)</span>
                        <span class="termin-price">Rp {{ number_format($termin->nominal, 0, ',', '.') }}</span>
                    </div>
                    <div class="termin-detail"><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($termin->tanggal_jatuh_tempo)->format('d F Y') }}</div>
                    <div class="termin-detail"><strong>Keterangan:</strong> {{ $termin->keterangan ?? 'Pembayaran Termin ' . $termin->termin_ke }}</div>
                    
                    @if($termin->nomor_va)
                        <div class="va-box">
                            <span class="va-label">Virtual Account Mandiri</span>
                            {{ substr($termin->nomor_va, 0, 4) }} {{ substr($termin->nomor_va, 4, 4) }} {{ substr($termin->nomor_va, 8) }}
                        </div>
                    @else
                        <div style="margin-top: 10px; font-size: 11px; color: #d97706; font-style: italic;">
                            *Nomor VA untuk termin ini akan diterbitkan setelah termin sebelumnya berstatus Lunas.
                        </div>
                    @endif
                </li>
                @endforeach
            </ul>

            <div class="btn-wrapper">
                <a href="{{ url('/klien/keuangan') }}" class="btn">Cek Dashboard Tagihan Anda</a>
            </div>
            
            <p style="margin-top: 25px; font-size: 12px; color: #71717a;"><strong>Catatan Penting:</strong> Harap lakukan pembayaran sebelum tanggal jatuh tempo agar pesanan Anda tidak dibatalkan secara otomatis oleh sistem.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BOMA SYS. Email ini dihasilkan otomatis oleh sistem.
        </div>
    </div>
</body>
</html>