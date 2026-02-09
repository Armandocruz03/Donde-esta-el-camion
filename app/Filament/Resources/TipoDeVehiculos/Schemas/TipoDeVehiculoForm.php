<?php

namespace App\Filament\Resources\TipoDeVehiculos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class TipoDeVehiculoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('capacidad')
                  ->options([
        'Camion 2T' => 'Camion 2T',
        'Camioneta de redilas 1T' => 'Camioneta de redilas 1T',
        'Carro pequeño 100kg' => 'Carro pequeño 100kg',
    ])
                    ->required(),
                TextInput::make('marca')
                    ->required(),
                TextInput::make('modelo')
                    ->required(),
            ]);
    }
}
