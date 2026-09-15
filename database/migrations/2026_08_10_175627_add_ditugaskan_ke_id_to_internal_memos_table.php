<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('internal_memos', function (Blueprint $table) {
        $table->unsignedBigInteger('ditugaskan_ke_id')->nullable()->after('no_im');
    });
}

public function down()
{
    Schema::table('internal_memos', function (Blueprint $table) {
        $table->dropColumn('ditugaskan_ke_id');
    });
}
};
