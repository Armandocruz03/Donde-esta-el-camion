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
        // CONDUCTOR
        // ==========================
        User::updateOrCreate(
            ['email' => 'conductor@test.com'],
            [
                'name' => 'Conductor',
                'password' => Hash::make('password'),
                'rol_id' => $conductorRol->id,
            ]
        );

        // ==========================
        // USUARIO
        // ==========================
        User::updateOrCreate(
            ['email' => 'usuario@test.com'],
            [
                'name' => 'Usuario',
                'password' => Hash::make('password'),
                'rol_id' => $usuarioRol->id,
            ]
        );
    }
}
