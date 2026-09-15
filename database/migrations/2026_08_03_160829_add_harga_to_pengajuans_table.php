<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            // Menggunakan bigInteger karena harga reklame bisa ratusan juta
            $table->bigInteger('estimasi_harga')->nullable()->after('selesai_sewa');
            $table->bigInteger('harga_final')->nullable()->after('estimasi_harga');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn(['estimasi_harga', 'harga_final']);
        });
    }
};