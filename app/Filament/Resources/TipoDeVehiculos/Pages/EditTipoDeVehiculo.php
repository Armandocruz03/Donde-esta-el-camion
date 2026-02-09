<?php

namespace App\Filament\Resources\TipoDeVehiculos\Pages;

use App\Filament\Resources\TipoDeVehiculos\TipoDeVehiculoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoDeVehiculo extends EditRecord
{
    protected static string $resource = TipoDeVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
