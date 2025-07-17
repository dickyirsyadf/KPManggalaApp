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
        Schema::create('slip_gaji', function (Blueprint $table) {
            $table->id();
            $table->string("id_karyawan");
            $table->string("nama");
            $table->string("jabatan");
            $table->string("periode");
            $table->integer("gaji_pokok");
            $table->integer("tunjangan_jabatan");
            $table->integer("pendapatan_lembur");
            $table->integer('total_pendapatan');
            $table->integer("potongan_absen");
            $table->integer("potongan_telat");
            $table->integer("total_potongan");
            $table->integer("gaji_bersih");
            $table->integer("jumlah_hadir");
            $table->integer("jumlah_sakit");
            $table->integer("jumlah_izin");
            $table->integer("jumlah_absen");
            $table->integer("jumlah_lembur");
            $table->integer("jumlah_terlambat");
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_gaji');
    }
};
