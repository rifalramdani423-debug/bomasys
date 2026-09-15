<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_qcs', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->string('anggota_tim')->nullable()->after('tim_pelaksana');
            $table->text('foto_hasil')->nullable()->after('foto_malam');
        });
    }

    public function down()
    {
        Schema::table('laporan_qcs', function (Blueprint $table) {
            $table->dropColumn(['anggota_tim', 'foto_hasil']);
        });
    }
};