<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_qcs', function (Blueprint $table) {
            $table->text('catatan_revisi')->nullable()->after('catatan_lapangan');
        });
    }

    public function down()
    {
        Schema::table('laporan_qcs', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });
    }
};