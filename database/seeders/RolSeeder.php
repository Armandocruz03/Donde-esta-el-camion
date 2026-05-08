<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        // Usamos una estructura de clave => valor para mayor claridad
        $roles = [
            1 => 'admin',
            2 => 'conductor',
            3 => 'usuario',
        ];

        foreach ($roles as $id => $nombre) {
            Rol::updateOrCreate(
                ['id' => $id], // Busca por ID para que siempre sea el 3
                ['nombre' => $nombre]
            );
        }
    }
}