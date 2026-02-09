<?php

namespace App\Filament\Resources\Vehiculos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehiculoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('placas'),
                TextEntry::make('modelo'),
                TextEntry::make('color')
                    ->placeholder('-'),
                TextEntry::make('dimensiones')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
