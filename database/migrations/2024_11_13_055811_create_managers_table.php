<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->foreignId('id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('nama', 50)->nullable(false);
            $table->foreignId('id_hakakses')->unsigned();
            $table->timestamps();
            $table->foreign('id_hakakses')->references('id')->on('hakakses');
            
        });

        DB::unprepared('
                CREATE TRIGGER insert_karyawan
                AFTER INSERT ON users
                FOR EACH ROW
                BEGIN
                    IF NEW.id_hakakses = 1 OR NEW.id_hakakses = 3 THEN
                        INSERT INTO managers (id, nama, id_hakakses, created_at, updated_at)
                        VALUES (NEW.id,NEW.nama,NEW.id_hakakses, NOW(), NOW());
                    END IF;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
