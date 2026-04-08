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
        Schema::create('imei', function (Blueprint $table) {
            $table->id();
            $table->string('imei')->unique();
            $table->foreignId('telefono_id')->nullable()->constrained('telefono')->onDelete('restrict');
            $table->text('caracteristicas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //eliminar la columna imeis_asociados de la tabla telefono
        Schema::table('telefono', function (Blueprint $table) {
            $table->dropColumn('imeis_asociados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imei');
        Schema::table('telefono', function (Blueprint $table) {
            $table->json('imeis_asociados')->nullable();
        });
    }
};
