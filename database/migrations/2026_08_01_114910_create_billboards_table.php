<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billboards', function (Blueprint $table) {
            $table->id();
            $table->string('kode_titik')->unique();
            $table->string('lokasi');
            $table->string('ukuran');
            $table->string('jenis_ooh');
            $table->string('kab_kota');
            $table->bigInteger('harga_per_bulan');
            $table->string('status')->default('Tersedia');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billboards');
    }
};
