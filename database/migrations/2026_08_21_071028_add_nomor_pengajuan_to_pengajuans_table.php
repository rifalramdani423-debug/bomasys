<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nomor pengajuan manusiawi (mis. PSN-2026-0001), terpisah dari primary key `id`.
     * `id` tetap dipakai untuk relasi/route seperti biasa; kolom ini murni untuk tampilan
     * ke pengguna (dashboard, email, dokumen) supaya tidak membocorkan volume transaksi
     * mentah lewat auto-increment PK.
     */
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->string('nomor_pengajuan')->nullable()->after('id');
        });

        // Backfill data lama: urutkan per tahun berdasarkan created_at, beri nomor urut.
        $pengajuans = DB::table('pengajuans')->orderBy('created_at')->get(['id', 'created_at']);
        $counters = [];

        foreach ($pengajuans as $row) {
            $year = \Carbon\Carbon::parse($row->created_at)->format('Y');
            $counters[$year] = ($counters[$year] ?? 0) + 1;
            $nomor = sprintf('PSN-%s-%04d', $year, $counters[$year]);

            DB::table('pengajuans')->where('id', $row->id)->update(['nomor_pengajuan' => $nomor]);
        }

        Schema::table('pengajuans', function (Blueprint $table) {
            $table->unique('nomor_pengajuan');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropUnique(['nomor_pengajuan']);
            $table->dropColumn('nomor_pengajuan');
        });
    }
};
