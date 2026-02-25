<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\RegistroCiudadanoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\RutaActualController;
use App\Http\Controllers\AuthCiudadanoController;



Route::get('/', [InicioController::class, 'index'])
    ->name('inicio');

//Login
Route::get('/login', [AuthCiudadanoController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthCiudadanoController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthCiudadanoController::class, 'logout'])
    ->name('logout');

// Importar GeoJSON
Route::get('/importar-geojson', [UbicacionController::class, 'importarGeojson']);

// Ver mapa por ruta
Route::get('/ruta/{rutaId}', [UbicacionController::class, 'verRuta']);

Route::get('/registro-ciudadano', [RegistroCiudadanoController::class, 'create'])
    ->name('registro.ciudadano');

Route::post('/registro-ciudadano', [RegistroCiudadanoController::class, 'store'])
    ->name('registro.ciudadano.store');
    
Route::get('/ruta-actual', [RutaActualController::class, 'index']);

Route::get('/ruta-paradas', [RutaActualController::class, 'paradas']);