<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daftar_gaji', function (Blueprint $table) {
            $table->id();
            $table->string("id_karyawan");
            $table->string("nama");
            $table->string("jabatan");
            // $table->string("periode_gaji");
            // $table->string("tanggal_hitung_gaji");
            $table->integer('gaji_pokok');
            $table->integer("jml_hr_kerja");
            $table->integer("jml_hadir");
            $table->integer("jml_absen");
            $table->integer("jml_izin");
            $table->integer("jml_sakit");
            $table->integer("jml_terlambat");
            $table->integer("jml_lembur");
            $table->integer("gaji_bersih");
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_gaji');
    }
};
