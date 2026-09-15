<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummySkripsiSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $klien = DB::table('users')->where('role', 'klien')->first();
        if (!$klien) {
            $this->command->error('Buat minimal 1 akun klien lewat register browser dulu!');
            return;
        }

        $klienId = $klien->id;
        $billboards = DB::table('billboards')->pluck('kode_titik')->toArray();
        if (empty($billboards)) {
            $this->command->error('Tabel Billboard kosong!');
            return;
        }

        // --- SKENARIO SIDANG SUPER LENGKAP ---
        $skenario = [
            // 1. Normal: Pakai Jasa Desain
            ['status' => 'Menunggu ACC', 'catat' => 'Klien meminta tim BOMA untuk membuatkan desain dari awal.', 'desain' => 1],
            
            // 2. Normal: Bawa Naskah Sendiri
            ['status' => 'Menunggu ACC', 'catat' => 'Klien melampirkan naskah siap cetak.', 'desain' => 0],
            
            // 3. Masalah: Naskah/Desain Klien Blur
            ['status' => 'Ditolak', 'catat' => 'REVISI NASKAH: Resolusi file PDF/gambar yang dikirim terlalu kecil dan pecah jika dicetak. Mohon upload ulang file High-Res.', 'desain' => 0],
            
            // 4. Masalah: Tidak Bayar Termin (Overdue/Menunggak)
            ['status' => 'Menunggu Pembayaran', 'catat' => 'Klien belum merespon tagihan. Follow up via WA.', 'desain' => 1, 'overdue' => true],
            
            // 5. Masalah: Bukti Transfer Keuangan Blur
            ['status' => 'Menunggu Pembayaran', 'catat' => null, 'desain' => 0, 'revisi' => 'REVISI KEUANGAN: Foto struk transfer terpotong dan gelap. Mohon foto ulang dengan jelas.'],
            
            // 6. Masalah: Foto Pemasangan Lapangan (Aset/Produksi) Blur
            ['status' => 'Menunggu Verifikasi', 'catat' => 'REVISI PRODUKSI: Laporan foto bukti tayang dari tim lapangan buram (diambil malam hari). Tunda BAST, minta tim foto ulang besok pagi.', 'desain' => 0],
            
            // 7. Sukses: Lunas
            ['status' => 'Lunas / Aktif', 'catat' => 'Pembayaran sesuai. Siap tayang.', 'desain' => 0],
            
            // 8. Sukses: Lunas (Desain dari BOMA)
            ['status' => 'Lunas / Aktif', 'catat' => 'Proses cetak selesai. Segera jadwalkan pemasangan.', 'desain' => 1],
            
            // 9. Masalah: Ditolak Admin
            ['status' => 'Ditolak', 'catat' => 'Titik billboard ini sedang dalam perbaikan konstruksi.', 'desain' => 0],
            
            // 10. Normal: Menunggu Termin
            ['status' => 'Menunggu Termin', 'catat' => 'Desain disetujui. Silakan atur termin pembayaran.', 'desain' => 1],
            
            // 11. SPESIAL SIDANG: Pemasangan Hari Ini (Notifikasi Aset)
            ['status' => 'Lunas / Aktif', 'catat' => 'Jadwal pemasangan HARI INI. Tim Aset/Produksi harap eksekusi dan unggah foto lapangan.', 'desain' => 0, 'pasang_hari_ini' => true],
        ];

        foreach ($skenario as $index => $sken) {
            
            // Logika untuk menentukan tanggal mulai sewa
            if (isset($sken['pasang_hari_ini'])) {
                // Set tanggal mulai sewa TEPAT HARI INI agar muncul di notif Aset
                $mulaiSewa = $now->format('Y-m-d');
            } else {
                // Selain itu, jadwalnya masih beberapa hari ke depan
                $mulaiSewa = $now->copy()->addDays(rand(5, 15))->format('Y-m-d');
            }

            $pengajuanId = DB::table('pengajuans')->insertGetId([
                'user_id' => $klienId,
                'mulai_sewa' => $mulaiSewa,
                'selesai_sewa' => Carbon::parse($mulaiSewa)->addMonths(rand(3, 12))->format('Y-m-d'),
                'status_pengajuan' => $sken['status'],
                'estimasi_harga' => 20000000,
                'harga_final' => 20000000,
                'jasa_desain' => $sken['desain'],
                'catatan_admin' => $sken['catat'],
                'created_at' => $now->copy()->subDays(rand(1, 10)),
                'updated_at' => $now,
            ]);

            $titik = $billboards[$index % count($billboards)];
            DB::table('pengajuan_details')->insert([
                'pengajuan_id' => $pengajuanId, 
                'kode_titik' => $titik
            ]);

            // Buat tagihan Termin jika status masuk dalam kategori ini
            if (in_array($sken['status'], ['Menunggu Pembayaran', 'Menunggu Verifikasi', 'Lunas / Aktif'])) {
                $statusTermin = 'Menunggu Pembayaran';
                $keterangan = null;
                $buktiPembayaran = null;
                
                // Menentukan status termin
                if ($sken['status'] == 'Lunas / Aktif') {
                    $statusTermin = 'Lunas';
                    $buktiPembayaran = 'dummy_bukti.jpg';
                } elseif ($sken['status'] == 'Menunggu Verifikasi') {
                    $statusTermin = 'Menunggu Verifikasi Admin';
                    $buktiPembayaran = 'dummy_bukti.jpg';
                } elseif (isset($sken['revisi'])) {
                    $statusTermin = 'Menunggu Revisi';
                    $keterangan = $sken['revisi'];
                    $buktiPembayaran = 'dummy_bukti.jpg'; 
                }

                // Menentukan Tanggal Jatuh Tempo (Manipulasi Overdue)
                if (isset($sken['overdue'])) {
                    // Mundurkan 7 hari ke belakang agar sistem membaca klien telat bayar
                    $jatuhTempo = $now->copy()->subDays(7)->format('Y-m-d');
                } else {
                    // Normal, jatuh tempo 3 hari ke depan
                    $jatuhTempo = $now->copy()->addDays(3)->format('Y-m-d');
                }

                DB::table('termins')->insert([
                    'pengajuan_id' => $pengajuanId,
                    'termin_ke' => 1,
                    'persentase' => 50,
                    'nominal' => 10000000,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'bukti_pembayaran' => $buktiPembayaran,
                    'status_termin' => $statusTermin,
                    'keterangan' => $keterangan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('BERHASIL! 11 Skenario Dummy Sidang (Termasuk Jadwal Hari Ini) Selesai Dibuat.');
    }
}