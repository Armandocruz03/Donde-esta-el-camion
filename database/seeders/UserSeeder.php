<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener roles
        $adminRol = Rol::where('nombre', 'admin')->first();
        $conductorRol = Rol::where('nombre', 'conductor')->first();
        $usuarioRol = Rol::where('nombre', 'usuario')->first();

        // ==========================
        // ADMIN
        // ==========================
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'rol_id' => $adminRol->id,
            ]
        );

        // ==========================
        // USUARIO
        // ==========================
        User::updateOrCreate(
            ['email' => 'usuario@test.com'],
            [
                'name' => 'Usuario General',
                'password' => Hash::make('password'),
                'rol_id' => $usuarioRol->id,
            ]
        );

        // ==========================
        // CONDUCTORES
        // ==========================
        $conductores = [
            'Carlos Méndez',
            'Luis Hernández',
            'Jorge Ramírez',
            'Miguel Torres',
            'Ricardo Gómez',
            'Antonio Vargas',
            'Fernando Cruz',
            'Eduardo Salas',
            'Raúl Martínez',
            'Sergio López',
        ];

        foreach ($conductores as $index => $nombre) {
            User::updateOrCreate(
                ['email' => 'conductor' . ($index + 1) . '@test.com'],
                [
                    'name' => $nombre,
                    'password' => Hash::make('password'),
                    'rol_id' => $conductorRol->id,
                ]
            );
        }
    }
}