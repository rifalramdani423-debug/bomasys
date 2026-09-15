<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Perusahaan BOMA Advertising
|--------------------------------------------------------------------------
|
| Data rekening tujuan pembayaran disimpan terpusat di file ini, terpisah
| dari tampilan (view). Tujuannya agar ketika data rekening berubah,
| perubahan cukup dilakukan di satu tempat dan tidak perlu menyunting
| file tampilan satu per satu.
|
| Cara memanggil dari Blade:  config('boma.rekening')
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Daftar Rekening Tujuan Pembayaran
    |--------------------------------------------------------------------------
    |
    | Tambah atau kurangi entri pada array di bawah ini sesuai rekening resmi
    | yang dimiliki perusahaan. Urutan array menentukan urutan tampil di layar.
    |
    | GANTI DATA DI BAWAH INI DENGAN REKENING ASLI BOMA ADVERTISING.
    |
    */

    'rekening' => [
        [
            'bank'      => 'Bank Central Asia (BCA)',
            'kode'      => 'BCA',
            'nomor'     => '1234567890',
            'atas_nama' => 'PT BOMA Advertising',
            'cabang'    => 'KCP Bandung Pasirkaliki',
            'warna'     => 'blue',
        ],
        [
            'bank'      => 'Bank Mandiri',
            'kode'      => 'MANDIRI',
            'nomor'     => '9876543210',
            'atas_nama' => 'PT BOMA Advertising',
            'cabang'    => 'KCP Bandung Asia Afrika',
            'warna'     => 'yellow',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Kontak Konfirmasi Pembayaran
    |--------------------------------------------------------------------------
    |
    | Nomor WhatsApp bagian keuangan yang dapat dihubungi klien apabila
    | pembayaran sudah dilakukan namun status belum berubah. Gunakan format
    | internasional tanpa tanda plus, contoh: 6281234567890
    |
    */

    'wa_keuangan'   => '6281234567890',
    'nama_keuangan' => 'Bagian Keuangan BOMA',

    /*
    |--------------------------------------------------------------------------
    | Batas Waktu Pembayaran
    |--------------------------------------------------------------------------
    |
    | Jumlah jam sejak tagihan diterbitkan sampai pembayaran diharapkan masuk.
    | Dipakai sebagai keterangan pada halaman instruksi pembayaran.
    |
    */

    'batas_bayar_jam' => 24,

];