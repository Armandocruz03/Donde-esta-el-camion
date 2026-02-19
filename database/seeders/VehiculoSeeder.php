<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;
use App\Models\TipoDeVehiculo;
use App\Models\User;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = TipoDeVehiculo::all();

        // Obtener conductores
        $conductores = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'conductor');
        })->get();

        Vehiculo::updateOrCreate(
            ['placas' => 'BAS-101-A'],
            [
                'anio' => 2022,
                'color' => 'Blanco',
                'tipo_de_vehiculo_id' => $tipos[0]->id,
                'user_id' => $conductores[0]->id ?? null,
            ]
        );

        Vehiculo::updateOrCreate(
            ['placas' => 'BAS-202-B'],
            [
                'anio' => 2021,
                'color' => 'Verde',
                'tipo_de_vehiculo_id' => $tipos[1]->id,
                'user_id' => $conductores[0]->id ?? null,
            ]
        );

        Vehiculo::updateOrCreate(
            ['placas' => 'BAS-303-C'],
            [
                'anio' => 2023,
                'color' => 'Azul',
                'tipo_de_vehiculo_id' => $tipos[2]->id,
                'user_id' => null,
            ]
        );

        Vehiculo::updateOrCreate(
            ['placas' => 'BAS-404-D'],
            [
                'anio' => 2020,
                'color' => 'Gris',
                'tipo_de_vehiculo_id' => $tipos[3]->id,
                'user_id' => null,
            ]
        );

        Vehiculo::updateOrCreate(
            ['placas' => 'BAS-505-E'],
            [
                'anio' => 2024,
                'color' => 'Rojo',
                'tipo_de_vehiculo_id' => $tipos[4]->id,
                'user_id' => null,
            ]
        );
    }
}
