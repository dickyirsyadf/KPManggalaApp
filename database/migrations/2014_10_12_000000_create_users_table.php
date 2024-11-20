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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->nullable(false);
            $table->string('email')->nullable(true);
            $table->string('no_hp', 14)->nullable(true);
            $table->foreignId('id_hakakses')->unsigned()->default(2);
            $table->string('password', 255)->nullable(false);
            $table->timestamps();

            $table->foreign('id_hakakses')->references('id')->on('hakakses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
