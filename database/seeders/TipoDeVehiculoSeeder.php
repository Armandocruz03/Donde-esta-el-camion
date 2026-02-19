<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDeVehiculo;

class TipoDeVehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'marca' => 'Freightliner',
                'modelo' => 'M2 106 Recolector Compactador',
                'capacidad' => '10 toneladas',
                'dimensiones' => '6m x 2.5m x 3m',
            ],
            [
                'marca' => 'International',
                'modelo' => 'HV607 Compactador Trasero',
                'capacidad' => '15 toneladas',
                'dimensiones' => '7m x 2.6m x 3.2m',
            ],
            [
                'marca' => 'Isuzu',
                'modelo' => 'ELF 600 Recolección Urbana',
                'capacidad' => '8 toneladas',
                'dimensiones' => '5.5m x 2.4m x 2.8m',
            ],
            [
                'marca' => 'Kenworth',
                'modelo' => 'T370 Compactador Industrial',
                'capacidad' => '20 toneladas',
                'dimensiones' => '8m x 2.7m x 3.5m',
            ],
            [
                'marca' => 'Nissan',
                'modelo' => 'Cabstar Recolección Ligera',
                'capacidad' => '5 toneladas',
                'dimensiones' => '4.8m x 2.2m x 2.6m',
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoDeVehiculo::updateOrCreate(
                [
                    'marca' => $tipo['marca'],
                    'modelo' => $tipo['modelo'],
                ],
                $tipo
            );
        }
    }
}
