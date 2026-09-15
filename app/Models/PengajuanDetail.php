<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    protected $fillable = ['pengajuan_id', 'kode_titik'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    // Ditautkan lewat kode_titik (bukan foreign key id) karena memang begitu titik
    // reklame direferensikan di seluruh sistem ini.
    public function billboard()
    {
        return $this->belongsTo(Billboard::class, 'kode_titik', 'kode_titik');
    }
}