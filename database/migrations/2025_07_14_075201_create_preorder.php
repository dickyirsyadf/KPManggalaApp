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
        Schema::create('preorder', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_karyawan');

            // Foreign Key to 'barang' table
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');

            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->integer('harga_beli');
            $table->integer('total_harga');
            $table->string('status')->default('Pending'); // Pending, Disetujui, Ditolak, Selesai
            $table->string('status_dirubah_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorders');
    }
};
