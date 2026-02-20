<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        // 🔥 Rol por defecto
        $data['rol_id'] = 3; // Cambia por tu ID real

        return $this->getUserModel()::create($data);
    }
}