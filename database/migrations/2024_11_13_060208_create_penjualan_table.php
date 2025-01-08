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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('id')->primary(); // Transaction ID (e.g., TRS202312001)
            $table->string('id_karyawan'); // Foreign key for user (employee)
            $table->date('tgl_penjualan'); // Sale date
            $table->decimal('total_bayar', 15, 2); // Total amount paid
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_karyawan')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
