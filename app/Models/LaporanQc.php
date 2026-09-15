<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanQc extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_im',
        'kode_titik',
        'jenis_pekerjaan',
        'tim_pelaksana',
        'anggota_tim',     // Tambahan Baru
        'foto_siang',
        'foto_malam',
        'foto_hasil',      // Tambahan Baru
        'catatan_lapangan',
        'tanggal_dikerjakan',
        'status'
    ];
}