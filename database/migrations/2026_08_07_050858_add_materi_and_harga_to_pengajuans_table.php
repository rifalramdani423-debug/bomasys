<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pengajuans', function (Blueprint $table) {
        // Fitur Materi Naskah
        $table->string('file_preview')->nullable()->after('selesai_sewa')->comment('Foto kecil untuk validasi admin');
        $table->string('link_materi')->nullable()->after('file_preview')->comment('Link GDrive/Mentahan dari klien');
        $table->boolean('butuh_jasa_desain')->default(false)->after('link_materi')->comment('Centang jika butuh jasa BOMA');
        
        // Fitur Transparansi Harga
        $table->text('catatan_harga')->nullable()->after('harga_final')->comment('Rincian harga dari admin (Pajak, Cetak, dll)');
    });
}

public function down()
{
    Schema::table('pengajuans', function (Blueprint $table) {
        $table->dropColumn(['file_preview', 'link_materi', 'butuh_jasa_desain', 'catatan_harga']);
    });
}
};
