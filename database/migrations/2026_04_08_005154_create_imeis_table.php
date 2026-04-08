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
            $table->string('imei');
            $table->text('caracteristicas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //eliminar la columna imeis_asociados de la tabla telefono
        Schema::table('telefono', function (Blueprint $table) {
            $table->dropColumn('imeis_asociados');
            $table->foreignId('imei_id')->nullable()->constrained('imei')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imei');
    }
};
