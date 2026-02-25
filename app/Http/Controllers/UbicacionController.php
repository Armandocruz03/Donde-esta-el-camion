<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


class RegistroCiudadanoController extends Controller
{
    public function create()
    {
        return view('Portada.registro'); // tu formulario
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telephone' => 'required',
            'adress' => 'required',
            'numero' => 'required',
            'colonia' => 'required',
            'municipio' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => 3, // 🔥 Aquí lo forzamos como ciudadano
            'telephone' => $request->telephone,
            'adress' => $request->adress,
            'numero' => $request->numero,
            'colonia' => $request->colonia,
            'municipio' => $request->municipio,
        ]);

        // 🔐 Login automático
        Auth::login($user);

        // 🚀 Redirección a portada
        return redirect()->route('inicio');
    }
    private function obtenerDireccion($lat, $lng)
{
    $apiKey = config('services.google.maps_key');

    $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
        'latlng' => $lat . ',' . $lng,
        'key' => $apiKey,
        'language' => 'es'
    ]);

    if ($response->successful() && isset($response['results'][0])) {
        return $response['results'][0]['formatted_address'];
    }

    return null;
}
}