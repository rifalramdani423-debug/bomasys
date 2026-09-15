<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_memos', function (Blueprint $table) {
            $table->id();
            $table->string('no_im')->unique();
            $table->string('tujuan');
            $table->string('perihal');
            $table->string('klien_visual');
            $table->string('kode_titik');
            $table->date('tanggal_target');
            $table->text('catatan')->nullable();
            $table->string('diterbitkan_oleh');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_memos');
    }
};