<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UbicacionController;


Route::get('/', function () {
    return view('Portada.inicio');
});

// Importar GeoJSON
Route::get('/importar-geojson', [UbicacionController::class, 'importarGeojson']);

// Ver mapa por ruta
Route::get('/ruta/{rutaId}', [UbicacionController::class, 'verRuta']);
