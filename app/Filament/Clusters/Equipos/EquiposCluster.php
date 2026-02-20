<?php

namespace App\Filament\Clusters\Equipos;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class EquiposCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;
    protected static ?int $navigationSort = 2;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
