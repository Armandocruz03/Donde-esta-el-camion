<?php

namespace App\Filament\Resources\Rutas\Pages;

use App\Filament\Resources\Rutas\RutaResource;
use App\Models\RutaPunto;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewRuta extends ViewRecord
{
    protected static string $resource = RutaResource::class;

    protected string $view = 'filament.resources.rutas.pages.view-ruta';

    public array $puntosIniciales = [];

    public ?float $stopLat = null;
    public ?float $stopLng = null;
    public array $stopDias = [];
    public string $stopNombre = '';
    public string $stopHora = '08:00';
    public int $stopDuracion = 10;

    public array $paradasIniciales = [];

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function prepararParada($lat, $lng)
    {
        $this->stopLat = $lat;
        $this->stopLng = $lng;

        $this->dispatch('open-modal', id: 'modalParada');
    }

    public function guardarParada()
    {
        \App\Models\Parada::create([
            'ruta_id' => $this->record->id,
            'nombre' => $this->stopNombre,
            'lat' => $this->stopLat,
            'lng' => $this->stopLng,
            'hora' => $this->stopHora,
            'duracion_minutos' => $this->stopDuracion,
            'dias' => $this->stopDias,
        ]);

        \Filament\Notifications\Notification::make()
            ->title('Parada creada')
            ->success()
            ->send();

        $this->dispatch('close-modal', id: 'modalParada');
    }

    public function mount($record): void
    {
        parent::mount($record);

        $this->puntosIniciales = $this->record->puntos()
            ->orderBy('orden')
            ->get(['lat', 'lng'])
            ->toArray();

        $this->paradasIniciales = $this->record->paradas()
            ->where('activa', true)
            ->get()
            ->map(function ($parada) {
                return [
                    'id' => $parada->id,
                    'nombre' => $parada->nombre,
                    'lat' => (float) $parada->lat,
                    'lng' => (float) $parada->lng,
                    'hora' => $parada->hora,
                    'duracion' => $parada->duracion_minutos,
                    'dias' => $parada->dias,
                ];
            })
            ->toArray();
    }

    public function guardarPuntos($puntos)
    {
        if (empty($puntos)) {
            Notification::make()
                ->title('No hay puntos para guardar')
                ->danger()
                ->send();
            return;
        }

        $this->record->puntos()->delete();

        foreach ($puntos as $index => $punto) {
            RutaPunto::create([
                'ruta_id' => $this->record->id,
                'lat'     => $punto['lat'],
                'lng'     => $punto['lng'],
                'orden'   => $index,
            ]);
        }

        Notification::make()
            ->title('Ruta actualizada correctamente')
            ->success()
            ->send();
    }

    public function limpiarPuntos()
    {
        $this->record->puntos()->delete();

        Notification::make()
            ->title('Puntos eliminados')
            ->success()
            ->send();
    }
}
