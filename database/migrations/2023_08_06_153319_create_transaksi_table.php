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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->unsigned();
            $table->string('no_transaksi', 25)->nullable(false);
            $table->foreignId('id_jenis_transaksi')->nullable(false);
            $table->string('nominal_transaksi')->nullable(false);
            $table->datetime('tanggal_transaksi')->nullable(false);
            $table->timestamps();

            $table->foreign('id_jenis_transaksi')->references('id')->on('jenis_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
