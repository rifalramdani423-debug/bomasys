<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Termin;

class PengingatTagihan extends Command
{
    // Nama panggilan robot
    protected $signature = 'bomasys:pengingat-tagihan';
    protected $description = 'Kirim email pengingat tagihan termin H-1, Hari H, dan H+1 otomatis ke klien';

    public function handle()
    {
        // Patokan Waktu
        $hariIni = Carbon::today();
        $besok = Carbon::tomorrow();
        $kemarin = Carbon::yesterday();

        // 1. Tarik HANYA termin yang BELUM DIBAYAR (Status bukan Lunas & bukan Menunggu Verifikasi Admin)
        $termins = Termin::with(['pengajuan.user'])
            ->where(function($query) {
                $query->whereNull('status_termin')
                      ->orWhere(function($q) {
                          $q->where('status_termin', '!=', 'Lunas')
                            ->where('status_termin', '!=', 'Menunggu Verifikasi Admin');
                      });
            })
            ->whereNotNull('tanggal_jatuh_tempo')
            ->get();

        if ($termins->isEmpty()) {
            $this->info("Tidak ada tagihan yang perlu diingatkan hari ini.");
            return;
        }

        foreach ($termins as $termin) {
            $klien = $termin->pengajuan->user ?? null;
            if (!$klien) continue;

            $tglJatuhTempo = Carbon::parse($termin->tanggal_jatuh_tempo)->startOfDay();
            $emailTujuan = $klien->email;
            $namaKlien = $klien->nama_perusahaan ?? $klien->name;
            $nominal = 'Rp ' . number_format($termin->nominal, 0, ',', '.');
            $pesananId = $termin->pengajuan->nomor_pengajuan;
            $terminKe = $termin->termin_ke;

            $subject = '';
            $pesan = '';

            // 2. KONDISI H-1 (PENGINGAT AWAL)
            if ($tglJatuhTempo->equalTo($besok)) {
                $subject = "⏳ PENGINGAT: Tagihan Termin #{$terminKe} Pesanan {$pesananId} Jatuh Tempo Besok";
                $pesan = "Halo {$namaKlien},\n\nIni adalah pesan pengingat otomatis dari BOMA Sys.\n\nTagihan Termin #{$terminKe} Anda untuk Pesanan {$pesananId} sebesar {$nominal} akan jatuh tempo pada BESOK hari (" . $tglJatuhTempo->format('d M Y') . ").\n\nMohon siapkan pembayaran Anda agar proses penayangan reklame berjalan lancar tanpa kendala.\n\nTerima kasih,\nTim Keuangan BOMA.";
            } 
            // 3. KONDISI HARI H (WAJIB BAYAR HARI INI)
            elseif ($tglJatuhTempo->equalTo($hariIni)) {
                $subject = "🚨 PENTING: Tagihan Termin #{$terminKe} Pesanan {$pesananId} Jatuh Tempo HARI INI";
                $pesan = "Halo {$namaKlien},\n\nKami menginformasikan bahwa tagihan Termin #{$terminKe} untuk Pesanan {$pesananId} sebesar {$nominal} JATUH TEMPO PADA HARI INI (" . $tglJatuhTempo->format('d M Y') . ").\n\nMohon segera lakukan pembayaran transfer dan unggah bukti pembayaran Anda ke dalam dashboard sistem BOMA Sys hari ini juga.\n\nTerima kasih,\nTim Keuangan BOMA.";
            } 
            // 4. KONDISI H+1 (PERINGATAN TERLAMBAT)
            elseif ($tglJatuhTempo->equalTo($kemarin)) {
                $subject = "⚠️ PERINGATAN: Tagihan Termin #{$terminKe} Pesanan {$pesananId} TELAH TERLAMBAT";
                $pesan = "Halo {$namaKlien},\n\nSistem kami mencatat bahwa tagihan Termin #{$terminKe} untuk Pesanan {$pesananId} sebesar {$nominal} TELAH MELEWATI batas waktu jatuh tempo (" . $tglJatuhTempo->format('d M Y') . ").\n\nSesuai dengan ketentuan yang berlaku, mohon segera selesaikan tunggakan Anda dalam waktu 1x24 jam untuk menghindari penghentian penayangan reklame sementara atau denda keterlambatan.\n\nAbaikan pesan ini jika Anda baru saja melakukan pembayaran.\n\nTerima kasih,\nTim Keuangan BOMA.";
            }

            // Eksekusi Pengiriman Email
            if ($subject !== '') {
                try {
                    Mail::raw($pesan, function ($message) use ($emailTujuan, $subject) {
                        $message->to($emailTujuan)
                                ->subject($subject);
                    });
                    $this->info("Berhasil mengirim email tagihan [{$subject}] ke {$emailTujuan}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke {$emailTujuan}: " . $e->getMessage());
                }
            }
        }
    }
}