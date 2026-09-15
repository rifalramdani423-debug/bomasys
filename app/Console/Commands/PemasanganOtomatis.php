<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PemasanganOtomatis extends Command
{
    protected $signature = 'bomasys:jadwal-otomatis';
    protected $description = 'Mengecek jadwal kalender H-0 dan menerbitkan IM Otomatis (dikirim via Email) setiap jam 08:00';

    public function handle()
    {
        $hariIni = Carbon::today()->format('Y-m-d');
        
        $pengajuans = \App\Models\Pengajuan::with(['details', 'user'])
            ->where('mulai_sewa', $hariIni)
            ->where('status_pengajuan', 'Lunas / Aktif')
            ->get();

        if ($pengajuans->isEmpty()) {
            $this->info("Tidak ada jadwal pemasangan untuk hari ini ({$hariIni}).");
            return;
        }

        foreach ($pengajuans as $pesanan) {
            $namaKlien = $pesanan->user->nama_perusahaan ?? $pesanan->user->name ?? 'Klien BOMA';
            $titikDipesan = $pesanan->details->pluck('kode_titik')->toArray();
            
            // Generate Nomor IM Otomatis
            $tahunIni = date('Y');
            $jumlahIm = \App\Models\InternalMemo::whereYear('created_at', $tahunIni)->count();
            $nomorUrut = str_pad($jumlahIm + 1, 3, '0', STR_PAD_LEFT);
            $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][date('n') - 1];
            $noIm = "{$nomorUrut}/BCC-IM/PSB/{$bulanRomawi}/{$tahunIni}";

            // Simpan IM ke Database agar masuk Arsip
            \App\Models\InternalMemo::create([
                'no_im' => $noIm,
                'tujuan' => 'Tim Produksi & Lapangan',
                'perihal' => 'Pemasangan Reklame Baru ' . $namaKlien,
                'klien_visual' => $namaKlien,
                'kode_titik' => json_encode($titikDipesan),
                'tanggal_target' => $hariIni,
                'catatan' => 'SYSTEM-GENERATED: Ini adalah tugas pemasangan hari H yang diotomatisasi oleh BOMA Sys.',
                'diterbitkan_oleh' => 'ROBOT BOMA SYS',
                'gambar_acuan' => null,
                'kebutuhan_foto' => json_encode(['Siang', 'Malam']), 
            ]);

            // Kirim Email Eksekusi ke Aset/Produksi (Kamu bisa pakai email dummy atau real)
            try {
                Mail::raw("Tugas Baru Hari Ini: \nPemasangan di Titik " . implode(', ', $titikDipesan) . "\nKlien: {$namaKlien}\nSegera lakukan eksekusi dan laporan QC di sistem.", function ($message) {
                    $message->to('produksi@bomasys.com')
                            ->subject('AUTO: Tugas Pemasangan ' . Carbon::today()->format('d M Y'));
                });
            } catch (\Exception $e) {
                // Abaikan jika mail gagal
            }

            $this->info("Berhasil menerbitkan IM {$noIm} untuk pemasangan {$namaKlien}.");
        }
    }
}