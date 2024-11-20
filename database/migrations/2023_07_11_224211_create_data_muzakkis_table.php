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
        Schema::create('data_muzakkis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->nullable(false);
            $table->string('alamat', 255)->nullable(false);
            $table->string('no_hp', 16)->unique()->nullable(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_muzakkis');
    }
};
