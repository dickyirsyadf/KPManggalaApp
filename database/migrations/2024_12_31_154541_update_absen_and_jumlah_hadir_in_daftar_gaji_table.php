<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAbsenAndJumlahHadirInDaftarGajiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daftar_gaji', function (Blueprint $table) {
            $table->integer('absen')->nullable()->change();
            $table->integer('jumlah_hadir')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daftar_gaji', function (Blueprint $table) {
            $table->integer('absen')->nullable(false)->change();
            $table->integer('jumlah_hadir')->nullable(false)->change();
        });
    }
}
