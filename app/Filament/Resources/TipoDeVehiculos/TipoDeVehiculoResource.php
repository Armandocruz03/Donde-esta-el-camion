<?php

namespace App\Filament\Resources\TipoDeVehiculos;

use App\Filament\Clusters\Equipos\EquiposCluster;
use App\Filament\Resources\TipoDeVehiculos\Pages\CreateTipoDeVehiculo;
use App\Filament\Resources\TipoDeVehiculos\Pages\EditTipoDeVehiculo;
use App\Filament\Resources\TipoDeVehiculos\Pages\ListTipoDeVehiculos;
use App\Filament\Resources\TipoDeVehiculos\Schemas\TipoDeVehiculoForm;
use App\Filament\Resources\TipoDeVehiculos\Tables\TipoDeVehiculosTable;
use App\Filament\Resources\TipoDeVehiculos\RelationManagers; 
use App\Models\TipoDeVehiculo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoDeVehiculoResource extends Resource
{
    protected static ?string $model = TipoDeVehiculo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static ?string $cluster = EquiposCluster::class;

    protected static ?string $recordTitleAttribute = 'TipoDeVehiculo';

    public static function form(Schema $schema): Schema
    {
        return TipoDeVehiculoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoDeVehiculosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VehiculosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTipoDeVehiculos::route('/'),
            'create' => CreateTipoDeVehiculo::route('/create'),
            'edit' => EditTipoDeVehiculo::route('/{record}/edit'),
        ];
    }
}
