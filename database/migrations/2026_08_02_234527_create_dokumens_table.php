<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('dokumens', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke Pemilik Dokumen (Klien)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // Relasi opsional ke Pesanan (Sangat berguna untuk Bukti Pembayaran pesanan tertentu)
        $table->foreignId('pengajuan_id')->nullable()->constrained('pengajuans')->onDelete('set null');
        
        // Jenis Dokumen (KTP, NPWP, atau Bukti Pembayaran)
        $table->string('jenis_dokumen');
        
        // Lokasi file disimpan di server
        $table->string('file_path');
        
        // Status verifikasi dokumen
        $table->enum('status', ['Menunggu Validasi', 'Valid', 'Ditolak'])->default('Menunggu Validasi');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
