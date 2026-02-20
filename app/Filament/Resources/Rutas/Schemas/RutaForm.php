<?php

namespace App\Filament\Resources\Rutas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\ColorPicker;

class RutaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                ColorPicker::make('color')
                    ->label('Color de la Ruta')
                    ->required()
                    ->default('#4CAF93'),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                Toggle::make('activa')
                    ->required(),
            ]);
    }
}
