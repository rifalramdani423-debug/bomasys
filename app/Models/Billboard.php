<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billboard extends Model
{
    protected $fillable = [
        'kode_titik', 'lokasi', 'ukuran', 'jenis_ooh', 
        'kab_kota', 'harga_per_bulan', 'status', 
        'latitude', 'longitude'
    ];
}