<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('internal_memos', function (Blueprint $table) {
            // Karena kode_titik sekarang menyimpan banyak titik (JSON array), kita ubah tipenya menjadi text
            $table->text('kode_titik')->change(); 
            
            // Tambahkan kolom baru
            $table->string('gambar_acuan')->nullable()->after('diterbitkan_oleh');
            $table->text('kebutuhan_foto')->nullable()->after('gambar_acuan');
        });
    }

    public function down()
    {
        Schema::table('internal_memos', function (Blueprint $table) {
            $table->string('kode_titik')->change(); // Kembalikan ke string jika di-rollback
            $table->dropColumn(['gambar_acuan', 'kebutuhan_foto']);
        });
    }
};