<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pasars', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada sebelum dibuat
            if (!Schema::hasColumn('pasars', 'kabupaten')) {
                $table->string('kabupaten')->after('nama_pasar')->nullable();
            }
            if (!Schema::hasColumn('pasars', 'kantor_cabang')) {
                $table->string('kantor_cabang')->after('kabupaten')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('pasars', function (Blueprint $table) {
            $table->dropColumn(['kabupaten', 'kantor_cabang']);
        });
    }
};