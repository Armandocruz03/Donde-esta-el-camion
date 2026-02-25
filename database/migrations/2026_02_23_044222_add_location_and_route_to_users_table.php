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
        Schema::table('users', function (Blueprint $table) {
            // lat/lng con buena precisión
            $table->decimal('lat', 10, 7)->nullable()->after('municipio');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');

            // ruta asignada por proximidad
            $table->foreignId('ruta_id')->nullable()->after('lng')
                ->constrained('rutas')
                ->nullOnDelete();

            // permiso de notificaciones
            $table->boolean('notifications_enabled')->default(false)->after('ruta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ruta_id');
            $table->dropColumn(['lat', 'lng', 'notifications_enabled']);
        });
    }
};
