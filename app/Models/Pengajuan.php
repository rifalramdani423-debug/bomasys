<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    // Tambahkan 'catatan_harga' di sini agar rincian penawaran bisa tersimpan
    // 'nomor_pengajuan' SENGAJA tidak dimasukkan ke fillable — nomor ini dibuat otomatis
    // oleh sistem (lihat booted() di bawah), bukan input dari luar.
    protected $fillable = [
        'user_id',
        'mulai_sewa',
        'selesai_sewa',
        'status_pengajuan',
        'estimasi_harga',
        'harga_final',
        'catatan_admin',
        'catatan_harga',
        'jasa_desain',
        'link_desain',
        'file_preview'
    ];

    protected static function booted(): void
    {
        static::creating(function (Pengajuan $pengajuan) {
            if (empty($pengajuan->nomor_pengajuan)) {
                $pengajuan->nomor_pengajuan = static::generateNomorPengajuan();
            }
        });
    }

    /**
     * Buat nomor pengajuan berformat PSN-{tahun}-{urutan 4 digit}, reset tiap tahun.
     * Mengikuti pola penomoran dokumen yang sama seperti nomor surat BAST.
     */
    public static function generateNomorPengajuan(): string
    {
        $year = now()->format('Y');
        $prefix = "PSN-{$year}-";

        $terakhir = static::query()
            ->where('nomor_pengajuan', 'like', $prefix . '%')
            ->orderByDesc('nomor_pengajuan')
            ->value('nomor_pengajuan');

        $urutBerikutnya = $terakhir ? ((int) substr($terakhir, -4)) + 1 : 1;

        return $prefix . str_pad($urutBerikutnya, 4, '0', STR_PAD_LEFT);
    }

    public function details()
    {
        return $this->hasMany(PengajuanDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function termins()
    {
        return $this->hasMany(Termin::class, 'pengajuan_id');
    }
}