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
        Schema::create('tunjangan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jabatan');
            $table->integer('tunjangan_jabatan');
            $table->integer('rate_lembur');
            $table->timestamps();

            $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunjangan');
    }
};
