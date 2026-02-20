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
        Schema::create('ruta_puntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained()->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->integer('orden'); // importante para dibujar en orden
            $table->timestamps();
        });

        Schema::create('paradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained()->cascadeOnDelete();

            $table->string('nombre');

            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);

            $table->time('hora'); // hora fija
            $table->integer('duracion_minutos')->default(10);

            // días de la semana en JSON
            $table->json('dias'); // ["lunes","miercoles","viernes"]

            $table->boolean('activa')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_puntos');
    }
};
