<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara massal
    protected $guarded = ['id'];

    // Relasi: Dokumen ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Dokumen ini untuk pesanan mana? (Khusus Bukti Pembayaran)
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}