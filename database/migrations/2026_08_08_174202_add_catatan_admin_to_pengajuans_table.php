<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pengajuans', function (Blueprint $table) {
        $table->text('catatan_admin')->nullable()->after('status_pengajuan');
    });
}

public function down()
{
    Schema::table('pengajuans', function (Blueprint $table) {
        $table->dropColumn('catatan_admin');
    });
}
};
