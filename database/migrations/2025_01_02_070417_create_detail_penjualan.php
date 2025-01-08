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
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('id_penjualan'); // Foreign key for sale transaction
            $table->unsignedBigInteger('id_obat'); // Foreign key for product
            $table->integer('qty'); // Quantity sold
            $table->decimal('harga', 15, 2); // Price per unit
            $table->decimal('subtotal', 15, 2); // Total for this item
            $table->decimal('margin', 15, 2); // Total for this item
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_penjualan')->references('id')->on('penjualan')->onDelete('cascade');
            $table->foreign('id_obat')->references('id')->on('obat')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
