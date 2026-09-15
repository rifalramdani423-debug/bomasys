<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            // Cek apakah kolom jasa_desain belum ada? Jika belum, buatkan.
            if (!Schema::hasColumn('pengajuans', 'jasa_desain')) {
                $table->boolean('jasa_desain')->default(false)->after('estimasi_harga');
            }
            
            // Cek apakah kolom link_desain belum ada? Jika belum, buatkan.
            if (!Schema::hasColumn('pengajuans', 'link_desain')) {
                $table->string('link_desain')->nullable()->after('jasa_desain');
            }
            
            // Cek apakah kolom file_preview belum ada? Jika belum, buatkan.
            if (!Schema::hasColumn('pengajuans', 'file_preview')) {
                $table->string('file_preview')->nullable()->after('link_desain');
            }
        });
    }

    public function down()
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn(['jasa_desain', 'link_desain', 'file_preview']);
        });
    }
};