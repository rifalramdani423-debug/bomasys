<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('termins', function (Blueprint $table) {
        // Menambahkan kolom nomor_va yang boleh kosong (nullable)
        $table->string('nomor_va')->nullable()->after('keterangan');
    });
}

public function down()
{
    Schema::table('termins', function (Blueprint $table) {
        $table->dropColumn('nomor_va');
    });
}
};
