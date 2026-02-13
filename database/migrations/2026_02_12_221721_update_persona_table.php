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
        Schema::table('persona', function (Blueprint $table) {
            $table->string('lugar_nacimiento', 250)->nullable()->after('fecha_nacimiento');
            $table->string('nombre_supuesto', 250)->nullable()->after('lugar_nacimiento');
            $table->string('complemento', 40)->nullable()->after('nombre_supuesto');
            $table->enum('genero', ['MASCULINO', 'FEMENINO'] )->nullable()->after('complemento');
            $table->enum('estado_civil', ['SOLTERO', 'CASADO', 'DIVORCIADO', 'VIUDO','CONYUGUE'] )->nullable()->after('genero');
            $table->string('nombre_conyuge', 250)->nullable()->after('estado_civil');
            $table->string('ocupacion', 150)->nullable()->after('nombre_conyuge');
            $table->foreignId('id_pais')->nullable()->constrained('pais')->onDelete('set null')->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            $table->dropColumn(['lugar_nacimiento', 'nombre_supuesto', 'complemento', 'genero', 'estado_civil', 'nombre_conyuge', 'ocupacion']);
            $table->dropForeign(['id_pais']);
            $table->dropColumn('id_pais');
        });
    }
};
