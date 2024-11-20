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
        Schema::create('daftar_gajis', function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->string("nama");
            $table->string("bagian");
            $table->integer('jumlah_hadir');
            $table->integer("gaji_perhari");
            $table->integer("absen");
            $table->integer("bonus");
            $table->integer("gaji_bersih");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_gajis');
    }
};
