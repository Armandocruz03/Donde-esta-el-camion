<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Ruta;

class MapaRutas extends Page
{
    protected string $view = 'filament.pages.mapa-rutas';

    public array $rutas = [];

    public function mount(): void
    {
        $this->rutas = Ruta::with(['puntos' => function ($q) {
            $q->orderBy('orden');
        }])->get()->map(function ($ruta) {
            return [
                'id'     => $ruta->id,
                'nombre' => $ruta->nombre,
                'color'  => $ruta->color,
                'puntos' => $ruta->puntos->map(fn ($p) => [
                    'lat' => (float) $p->lat,
                    'lng' => (float) $p->lng,
                ]),
            ];
        })->toArray();
    }
}