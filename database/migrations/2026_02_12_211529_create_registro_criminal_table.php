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

        Schema::create('registro_criminal', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_registro');
            $table->string('especialidad', 250)->nullable();
            $table->text('rasgos')->nullable();
            $table->foreignId('id_persona')->constrained('persona')->onDelete('restrict');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_division')->constrained('division')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_criminal');
    }
};
