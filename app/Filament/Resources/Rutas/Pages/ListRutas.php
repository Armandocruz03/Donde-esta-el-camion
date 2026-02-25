<?php

namespace App\Filament\Resources\Rutas\Pages;

use App\Filament\Resources\Rutas\RutaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Models\Ruta;

class ListRutas extends ListRecords
{
    protected static string $resource = RutaResource::class;

    protected array $estadoAnterior = [];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        foreach (Ruta::all() as $ruta) {
            $this->estadoAnterior[$ruta->id] = $ruta->estado;
        }
    }

    protected function afterTableUpdated(): void
    {
        foreach (Ruta::all() as $ruta) {

            $estadoPrevio = $this->estadoAnterior[$ruta->id] ?? null;

            if ($estadoPrevio === 'en_ruta' && $ruta->estado === 'detenida') {

                Notification::make()
                    ->title('🏁 Ruta detenida')
                    ->body('La ruta ' . $ruta->nombre . ' ha finalizado.')
                    ->danger()
                    ->send();
            }

            $this->estadoAnterior[$ruta->id] = $ruta->estado;
        }
    }
}