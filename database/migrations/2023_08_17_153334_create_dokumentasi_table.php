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
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable(false);
            // $table->foreignId('dokumentasi_fotos_id')->unsigned();
            $table->text('deskripsi')->nullable(false);
            $table->date('tanggal_dokumentasi')->nullable(false);
            $table->enum('status', ['hapus'])->nullable();
            $table->timestamps();

            // $table->foreign('dokumentasi_fotos_id')->references('id')->on('dokumentasi_fotos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
