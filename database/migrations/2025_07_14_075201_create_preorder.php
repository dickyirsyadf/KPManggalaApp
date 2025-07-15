<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Trig\Tangent;

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
            $table->string('id_barang');
            $table->string('nama_barang');
            $table->integer('jumlah')->default(0);
            $table->integer('harga_beli')->default(0);
            $table->integer('total_harga')->default(0);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('status_dirubah')->nullable(); // Karyawan yang menyetujui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorder');
    }
};
