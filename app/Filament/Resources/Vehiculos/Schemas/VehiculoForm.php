<?php

namespace App\Filament\Resources\Vehiculos\Schemas;

use App\Models\User;
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
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('anio')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(now()->year),

                TextInput::make('color')
                    ->nullable()
                    ->maxLength(100),

                Select::make('user_id')
                    ->label('Conductor')
                    ->options(
                        User::whereHas('rol', function ($q) {
                            $q->where('nombre', 'conductor');
                        })->pluck('name', 'id')
                    )
                    ->searchable()
                    ->nullable(),
            ]);
    }
}
