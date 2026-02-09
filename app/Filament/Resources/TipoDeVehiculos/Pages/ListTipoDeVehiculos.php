<?php

namespace App\Filament\Resources\TipoDeVehiculos\Pages;

use App\Filament\Resources\TipoDeVehiculos\TipoDeVehiculoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoDeVehiculos extends ListRecords
{
    protected static string $resource = TipoDeVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
