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
        Schema::table('mandamiento', function (Blueprint $table) {
            // convertir hojaruta en null
            $table->string('hoja_ruta', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mandamiento', function (Blueprint $table) {
            $table->string('hoja_ruta', 100)->nullable(false)->change();
        });
    }
};
