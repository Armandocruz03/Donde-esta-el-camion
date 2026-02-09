<?php

namespace App\Filament\Resources\Vehiculos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;


class VehiculoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('placas')
                    ->required(),
                TextInput::make('modelo')
                    ->required(),
                ColorPicker::make('color'),
                TextInput::make('dimensiones'),
                Select::make('Tipo_de_Vehiculo_id')
    ->             relationship(name: 'TipoDeVehiculo', titleAttribute: 'capacidad')
            ]);
    }
}
