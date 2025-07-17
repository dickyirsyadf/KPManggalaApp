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
        Schema::create('kontrak_iklan', function (Blueprint $table) {
            $table->string('id_kontrak', 10)->primary();
            $table->string('nama_client');
            $table->string('nama_media');
            $table->integer('durasi');
            $table->integer('biaya_iklan');
            $table->date('tanggal_mulai_kontrak');
            $table->date('tanggal_selesai_kontrak');
            $table->enum('status', ['Dalam Pengajuan', 'Diterima', 'Ditolak', 'Sedang Tayang', 'Kontrak Selesai'])->default('Dalam Pengajuan');
            $table->string('diajukan_oleh');
            $table->string('dikonfirmasi_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_iklan');
    }
};
