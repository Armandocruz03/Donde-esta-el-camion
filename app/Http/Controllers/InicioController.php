<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        $ruta = null;
        $puntoActual = null;

        if (auth()->check()) {

            $ruta = auth()->user()->ruta;

            if ($ruta && $ruta->estado === 'en_ruta' && $ruta->punto_actual !== null) {

                $puntoActual = $ruta->puntos()
                    ->orderBy('orden')
                    ->skip($ruta->punto_actual)
                    ->first();
            }
        }

        return view('Portada.inicio', compact('ruta', 'puntoActual'));
    }
}