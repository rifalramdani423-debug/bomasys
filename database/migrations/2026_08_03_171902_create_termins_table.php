<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('termins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');
            $table->integer('termin_ke');
            $table->decimal('persentase', 5, 2); 
            $table->bigInteger('nominal');      
            $table->date('tanggal_jatuh_tempo');
            // TAMBAHKAN KOLOM INI UNTUK MENYIMPAN VA MIDTRANS
            $table->string('nomor_va')->nullable(); 
            $table->string('keterangan')->nullable(); // Menambahkan keterangan yang sepertinya terlewat
            $table->string('bukti_pembayaran')->nullable(); // Menambahkan bukti pembayaran
            $table->string('status_termin')->default('Menunggu Pembayaran');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('termins');
    }
};