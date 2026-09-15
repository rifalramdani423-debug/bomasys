<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // --- AKUN ADMIN / SUPERADMIN ---
            [
                'name' => 'Rifal Ramdani',
                'email' => 'rifalramdani423@gmail.com',
                'nama_perusahaan' => null,
                'no_wa' => '085893442377',
                'role' => 'superadmin',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Fall',
                'email' => 'akunfall423@gmail.com',
                'nama_perusahaan' => null,
                'no_wa' => '085823230707',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],
            
            // --- AKUN DIVISI INTERNAL ---
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky.aset@boma.com',
                'nama_perusahaan' => null,
                'no_wa' => '0858987654321',
                'role' => 'aset',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Firman Utina',
                'email' => 'firman.gm@boma.com',
                'nama_perusahaan' => null,
                'no_wa' => '085812345678',
                'role' => 'gm',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Sri Wahyuni',
                'email' => 'sri.keuangan@boma.com',
                'nama_perusahaan' => null,
                'no_wa' => '085843211234',
                'role' => 'keuangan',
                'password' => Hash::make('password123'),
            ],
            
            // --- AKUN TIM LAPANGAN / PRODUKSI ---
            [
                'name' => 'Cengkir Prasetyo',
                'email' => 'anggota50@boma.com',
                'nama_perusahaan' => null,
                'no_wa' => '086699661749',
                'role' => 'produksi',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Upik Arta Budiman',
                'email' => 'anggota49@boma.com',
                'nama_perusahaan' => null,
                'no_wa' => '0829288109799',
                'role' => 'produksi',
                'password' => Hash::make('password123'),
            ],
            
            // --- AKUN KLIEN DUMMY (Untuk Testing) ---
            [
                'name' => 'Klien Dummy (Bpk. Budi)',
                'email' => 'klien@gmail.com',
                'nama_perusahaan' => 'PT Makmur Jaya',
                'no_wa' => '081234567890',
                'role' => 'klien',
                'password' => Hash::make('password123'),
            ],
        ];

        DB::table('users')->insert($users);
    }
}