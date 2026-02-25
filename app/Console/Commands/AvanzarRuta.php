<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ruta;
use App\Models\RutaPunto;
use Illuminate\Support\Facades\Http;

class AvanzarRuta extends Command
{
    protected $signature = 'ruta:avanzar';
    protected $description = 'Simula avance del camion en rutas activas';

    public function handle()
    {
        // 1) Tomar una ruta en ejecución
        $ruta = Ruta::where('estado', 'en_ruta')->first();

        if (!$ruta) {
            $this->info("No hay ruta activa.");
            return Command::SUCCESS;
        }

        // 2) Contar puntos
       

        if ($totalPuntos === 0) {
            $this->info("Ruta sin puntos.");
            return Command::SUCCESS;
        }

        $ultimoIndice = $totalPuntos - 1;

        // 3) Si ya está en el último punto, detener ruta
        if ((int) $ruta->punto_actual >= $ultimoIndice) {
            $ruta->punto_actual = $ultimoIndice;
            $ruta->estado = 'detenida';
            $ruta->ultima_actualizacion = now();
            $ruta->save();

            $this->info("Ruta finalizada correctamente.");
            return Command::SUCCESS;
        }

        // 4) Avanzar punto
        $ruta->punto_actual = (int) $ruta->punto_actual + 1;
        $ruta->ultima_actualizacion = now();
        $ruta->save();

        // 5) Obtener el punto actual (el nuevo)
       

        if (!$punto) {
            $this->warn("No se encontró el punto actual.");
            return Command::SUCCESS;
        }

        // 6) ✅ Consultar Google SOLO si la calle está vacía
        if (empty($punto->calle)) {
            $punto->calle = $this->reverseGeocode($punto->lat, $punto->lng);
            $punto->save();
        }

        $this->info("Avanzó al punto: {$ruta->punto_actual} | Calle: {$punto->calle}");

        return Command::SUCCESS;
    }

    /**
     * Convierte lat/lng en una dirección o calle usando Google Geocoding.
     * Esto se ejecuta SOLO cuando cambia el punto.
     */
    private function reverseGeocode(float $lat, float $lng): string
    {
        $apiKey = config('services.google_maps.key');

        if (!$apiKey) {
            return 'API no configurada';
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => $lat . ',' . $lng,
                'key' => $apiKey,
                'language' => 'es',
            ]);

            if (!$response->successful()) {
                return 'Error consultando Google';
            }

            $data = $response->json();

            // Si hay resultados, tomar el formatted_address (más confiable que route)
            if (!empty($data['results'][0]['formatted_address'])) {
                return $data['results'][0]['formatted_address'];
            }

            return 'Sin dirección registrada';
        } catch (\Throwable $e) {
            return 'Error geocoding';
        }
    }
}