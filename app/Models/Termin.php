<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termin extends Model
{
    protected $fillable = [
        'pengajuan_id', 
        'termin_ke', 
        'persentase', 
        'nominal', 
        'tanggal_jatuh_tempo', 
        'keterangan',
        'bukti_pembayaran',
        'status_termin',
        'nomor_va' // <--- INI DIA BIANG KEROKNYA! Sekarang sudah didaftarkan.
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}