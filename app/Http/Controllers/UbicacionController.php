<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    /**
     * Importar GeoJSON a rutas y ubicaciones
     */
    public function importarGeojson()
    {
        $path = public_path('maps/map.geojson');

        if (!file_exists($path)) {
            return response()->json([
                'error' => 'map.geojson no encontrado'
            ], 404);
        }

        $geojson = json_decode(file_get_contents($path), true);

        foreach ($geojson['features'] as $feature) {

            if (($feature['geometry']['type'] ?? null) !== 'Polygon') {
                continue;
            }

            // Crear o actualizar la ruta por nombre
            $nombreRuta = $feature['properties']['name'] ?? 'Ruta sin nombre';

            $ruta = Ruta::firstOrCreate(
                ['nombre' => $nombreRuta],
                [
                    'descripcion' => 'Importada desde GeoJSON',
                    'activa' => true,
                ]
            );

            // Borrar ubicaciones previas
            Ubicacion::where('ruta_id', $ruta->id)->delete();

            // Insertar puntos
            $orden = 1;

            foreach ($feature['geometry']['coordinates'] as $ring) {
                foreach ($ring as $coord) {

                    Ubicacion::create([
                        'ruta_id' => $ruta->id,
                        'orden'   => $orden,
                        'nombre'  => "Punto $orden",
                        'lng'     => $coord[0],
                        'lat'     => $coord[1],
                    ]);

                    $orden++;
                }
            }
        }

        return response()->json([
            'mensaje' => 'Rutas y ubicaciones importadas correctamente'
        ]);
    }

    /**
     * Mostrar el mapa de una ruta por URL
     */
    public function verRuta($rutaId)
    {
        $ruta = Ruta::findOrFail($rutaId);

        $ubicaciones = Ubicacion::where('ruta_id', $ruta->id)
            ->orderBy('orden')
            ->get();

        if ($ubicaciones->isEmpty()) {
            abort(404, 'La ruta no tiene ubicaciones');
        }

        return view('mapa', compact('ruta', 'ubicaciones'));
    }
}
