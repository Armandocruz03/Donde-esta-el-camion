<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para añadir la columna.
     */
    public function up(): void
    {
        Schema::table('tipo_de_vehiculos', function (Blueprint $table) {
            // Guardamos la ruta del archivo (string). 
            // 'nullable' permite que existan registros sin foto.
            // 'after' posiciona la columna visualmente después de 'modelo'.
            $table->string('imagen')->nullable()->after('modelo');
        });
    }

    /**
     * Revierte la migración eliminando la columna.
     */
    public function down(): void
    {
        Schema::table('tipo_de_vehiculos', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};