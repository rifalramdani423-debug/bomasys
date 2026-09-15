<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_qcs', function (Blueprint $table) {
            $table->id();
            $table->string('no_im')->nullable(); // Referensi nomor Internal Memo
            $table->string('kode_titik'); // Contoh: BM 12
            $table->string('jenis_pekerjaan'); // PSB, MNT, CHK
            $table->string('tim_pelaksana')->nullable(); 
            $table->string('foto_siang')->nullable(); // Path gambar di storage
            $table->string('foto_malam')->nullable(); // Path gambar di storage
            $table->text('catatan_lapangan')->nullable();
            $table->enum('status', ['Menunggu Validasi', 'Revisi', 'Disetujui'])->default('Menunggu Validasi');
            $table->date('tanggal_dikerjakan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_qcs');
    }
};