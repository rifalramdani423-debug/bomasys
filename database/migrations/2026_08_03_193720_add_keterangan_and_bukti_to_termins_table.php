<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('termins', function (Blueprint $table) {
        $table->string('keterangan')->nullable()->after('tanggal_jatuh_tempo');
        $table->string('bukti_pembayaran')->nullable()->after('keterangan');
    });
}

public function down()
{
    Schema::table('termins', function (Blueprint $table) {
        $table->dropColumn(['keterangan', 'bukti_pembayaran']);
    });
}
};
