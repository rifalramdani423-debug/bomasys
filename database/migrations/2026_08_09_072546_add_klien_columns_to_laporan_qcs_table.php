<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_qcs', function (Blueprint $table) {
            $table->boolean('dibagikan_ke_klien')->default(false)->after('status');
            $table->text('foto_klien')->nullable()->after('dibagikan_ke_klien');
        });
    }

    public function down()
    {
        Schema::table('laporan_qcs', function (Blueprint $table) {
            $table->dropColumn(['dibagikan_ke_klien', 'foto_klien']);
        });
    }
};