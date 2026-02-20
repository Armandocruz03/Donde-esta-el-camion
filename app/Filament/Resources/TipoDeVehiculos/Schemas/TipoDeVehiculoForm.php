<?php

namespace App\Filament\Resources\TipoDeVehiculos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TipoDeVehiculoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('capacidad')
                    ->options([
                        '5 toneladas' => '5 toneladas',
                        '8 toneladas' => '8 toneladas',
                        '10 toneladas' => '10 toneladas',
                        '15 toneladas' => '15 toneladas',
                        '20 toneladas' => '20 toneladas',
                    ])
                    ->required()
                    ->label('Capacidad de carga'),

                TextInput::make('marca')
                    ->required()
                    ->maxLength(255)
                    ->label('Marca'),

                TextInput::make('modelo')
                    ->required()
                    ->maxLength(255)
                    ->label('Modelo'),

                TextInput::make('dimensiones')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. 6m x 2.5m x 3m')
                    ->label('Dimensiones'),
            ]);
    }
}