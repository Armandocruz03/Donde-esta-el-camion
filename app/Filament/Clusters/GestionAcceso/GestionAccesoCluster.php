<?php

namespace App\Filament\Clusters\GestionAcceso;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class GestionAccesoCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;
    protected static ?string $navigationLabel = 'Gestión de Acceso';
    protected static ?int $navigationSort = 1;
}
