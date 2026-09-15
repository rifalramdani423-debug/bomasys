<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalMemo extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_im',
        'tujuan',
        'perihal',
        'klien_visual',
        'kode_titik',
        'tanggal_target',
        'catatan',
        'diterbitkan_oleh',
        'gambar_acuan',   // Tambahan Baru
        'kebutuhan_foto', // Tambahan Baru
    ];
}