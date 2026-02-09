<?php

namespace App\Filament\Resources\Ubicacions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UbicacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ruta_id')
                    ->required()
                    ->numeric(),
                TextInput::make('orden')
                    ->required()
                    ->numeric(),
                TextInput::make('nombre'),
                TextInput::make('lat')
                    ->required()
                    ->numeric(),
                TextInput::make('lng')
                    ->required()
                    ->numeric(),
            ]);
    }
}
