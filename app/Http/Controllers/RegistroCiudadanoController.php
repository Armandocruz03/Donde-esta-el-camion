<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistroCiudadanoController extends Controller
{
    public function create()
    {
        return view('Portada.registro');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',

            'telephone' => 'required|string|max:20',
            'adress'    => 'required|string|max:255',
            'numero'    => 'required|string|max:20',
            'colonia'   => 'required|string|max:255',
            'municipio' => 'required|string|max:255',

            // Ubicación opcional (si no acepta, será null)
            'lat'       => 'nullable|numeric|between:-90,90',
            'lng'       => 'nullable|numeric|between:-180,180',

            // Permiso de notificaciones (checkbox)
            'notifications_enabled' => 'nullable|boolean',
        ]);

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        // 1) Detectar ruta si hay ubicación
        $rutaId = null;
        if (!is_null($lat) && !is_null($lng)) {
            $rutaId = $this->detectarRutaMasCercana($lat, $lng, 0.25); // 0.25 km = 250 m
        }

        // 2) Crear usuario forzando rol_id = 3
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),

            'rol_id'   => 3, // ✅ Ciudadano SIEMPRE

            'telephone' => $request->telephone,
            'adress'    => $request->adress,
            'numero'    => $request->numero,
            'colonia'   => $request->colonia,
            'municipio' => $request->municipio,

            'lat'       => $lat,
            'lng'       => $lng,
            'ruta_id'   => $rutaId,

            'notifications_enabled' => (bool) $request->boolean('notifications_enabled'),
        ]);

        // 3) Login automático
        Auth::login($user);

        // 4) Redirige a inicio
        return redirect()->route('inicio')
            ->with('success', 'Registro exitoso. Bienvenido, ' . $user->name);
    }

    /**
     * Devuelve ruta_id si el usuario está dentro del radio (km) de la polilínea,
     * aproximando por cercanía a puntos de la ruta.
     */
    private function detectarRutaMasCercana(float $latUsuario, float $lngUsuario, float $radioKm = 0.25): ?int
    {
        // Cargamos rutas activas con puntos
        $rutas = Ruta::where('activa', true)->with('puntos')->get();

        $mejorRutaId = null;
        $mejorDist = INF;

        foreach ($rutas as $ruta) {
            foreach ($ruta->puntos as $punto) {
                $dist = $this->haversineKm($latUsuario, $lngUsuario, (float) $punto->lat, (float) $punto->lng);

                if ($dist < $mejorDist) {
                    $mejorDist = $dist;
                    $mejorRutaId = $ruta->id;
                }
            }
        }

        // Si la mejor distancia está dentro del radio, asignamos esa ruta; si no, null
        if ($mejorDist <= $radioKm) {
            return $mejorRutaId;
        }

        return null;
    }

    /**
     * Distancia Haversine (km) entre 2 coordenadas.
     */
    private function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371; // radio tierra km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }
}