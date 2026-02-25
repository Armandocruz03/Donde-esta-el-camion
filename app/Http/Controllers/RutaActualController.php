<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaActualController extends Controller
{
    public function index()
{
    // Buscar ruta en curso
    $ruta = Ruta::where('estado', 'en_ruta')->first();

    // 🔴 SI NO HAY RUTA EN CURSO
    if (!$ruta) {

        $ruta = Ruta::where('estado', 'detenida')
            ->latest('updated_at')
            ->first();

        if (!$ruta) {
            return response()->json(['estado' => 'detenida']);
        }

        $paradas = $ruta->paradas()->orderBy('id')->get();

        if ($paradas->isEmpty()) {
            return response()->json(['estado' => 'detenida']);
        }

        $ultima = $paradas->last();

        return response()->json([
            'estado' => 'detenida',
            'punto'  => $ruta->punto_actual ?? 0,
            'calle'  => $ultima->nombre,
            'lat'    => $ultima->lat,
            'lng'    => $ultima->lng,
        ]);
    }

    // 🟢 RUTA EN CURSO

    // IMPORTANTE: DEFINIR PARADAS AQUÍ
    $paradas = $ruta->paradas()->orderBy('id')->get();

    if ($paradas->isEmpty()) {
        return response()->json(['estado' => 'detenida']);
    }

    $index = (int) $ruta->punto_actual;

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $paradas->count()) {
        $index = $paradas->count() - 1;
    }

    // ⏱ CONTROL DE TIEMPO
    if ($ruta->ultima_actualizacion) {

        $segundos = now()->timestamp - strtotime($ruta->ultima_actualizacion);
        $intervalo = $ruta->segundos_avance ?? 60;

        if ($segundos >= $intervalo) {

            $index++;

            if ($index >= $paradas->count()) {

                $ruta->estado = 'detenida';
                $ruta->punto_actual = $paradas->count() - 1;
                $ruta->save();

                $ultima = $paradas->last();

                return response()->json([
                    'estado' => 'detenida',
                    'punto'  => $ruta->punto_actual,
                    'calle'  => $ultima->nombre,
                    'lat'    => $ultima->lat,
                    'lng'    => $ultima->lng,
                ]);
            }

            $ruta->punto_actual = $index;
            $ruta->ultima_actualizacion = now();
            $ruta->save();
        }
    } else {
        $ruta->ultima_actualizacion = now();
        $ruta->save();
    }

    $ruta->refresh();
    $index = (int) $ruta->punto_actual;

    if (!isset($paradas[$index])) {

        $ultima = $paradas->last();

        return response()->json([
            'estado' => 'detenida',
            'punto'  => $ruta->punto_actual,
            'calle'  => $ultima->nombre,
            'lat'    => $ultima->lat,
            'lng'    => $ultima->lng,
        ]);
    }

    $paradaActual = $paradas[$index];

    return response()->json([
        'estado' => $ruta->estado,
        'punto'  => $ruta->punto_actual,
        'calle'  => $paradaActual->nombre,
        'lat'    => $paradaActual->lat,
        'lng'    => $paradaActual->lng,
    ]);
}

    public function iniciar($id)
{
    $ruta = Ruta::findOrFail($id);

    $ruta->estado = 'en_ruta';
    $ruta->punto_actual = 0; // empezar desde la primera parada
    $ruta->ultima_actualizacion = now();
    $ruta->save();

    return redirect()->back()->with('success', 'Ruta iniciada correctamente');
}
     // Dibujarlinea de paradas   
    public function paradas()
        {
            $ruta = \App\Models\Ruta::where('estado', 'en_ruta')->first();

            if (!$ruta) {
                return response()->json([]);
            }

            $paradas = $ruta->paradas()
                ->orderBy('id')
                ->get(['lat','lng','nombre']);

            return response()->json($paradas);
        }
}