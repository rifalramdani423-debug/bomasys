<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Wajib ditambahkan untuk jurus sapu bersih
use Faker\Factory as Faker;

class ProduksiSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // JURUS SAPU BERSIH: Hapus permanen HANYA akun produksi lama yang nyangkut
        // Ini akan mem-bypass SoftDeletes sehingga email tidak akan bentrok lagi
        DB::table('users')->where('role', 'produksi')->delete();

        // 1. BUAT 5 KETUA TIM (KHUSUS PRIA)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'nik'      => 'BOMA-PRO-2026-K0' . $i,
                'name'     => 'Ketua Tim ' . $i . ' (' . $faker->firstName('male') . ')',
                'email'    => 'ketua' . $i . '@boma.com',
                'no_wa'    => $faker->phoneNumber,
                'divisi'   => 'Ketua Tim Lapangan',
                'role'     => 'produksi',
                'password' => Hash::make('password'),
            ]);
        }

        // 2. BUAT 50 ANGGOTA BIASA (KHUSUS PRIA)
        for ($i = 1; $i <= 50; $i++) {
            $nomorUrut = str_pad($i, 3, '0', STR_PAD_LEFT);
            User::create([
                'nik'      => 'BOMA-PRO-2026-A' . $nomorUrut,
                'name'     => $faker->name('male'),
                'email'    => 'anggota' . $i . '@boma.com',
                'no_wa'    => $faker->phoneNumber,
                'divisi'   => 'Anggota Lapangan',
                'role'     => 'produksi',
                'password' => Hash::make('password'),
            ]);
        }
    }
}