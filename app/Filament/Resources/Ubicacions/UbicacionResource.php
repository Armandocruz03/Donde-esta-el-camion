<?php

namespace App\Filament\Resources\Ubicacions;

use App\Filament\Resources\Ubicacions\Pages\CreateUbicacion;
use App\Filament\Resources\Ubicacions\Pages\EditUbicacion;
use App\Filament\Resources\Ubicacions\Pages\ListUbicacions;
use App\Filament\Resources\Ubicacions\Schemas\UbicacionForm;
use App\Filament\Resources\Ubicacions\Tables\UbicacionsTable;
use App\Models\Ubicacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UbicacionResource extends Resource
{
    protected static ?string $model = Ubicacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Ubicacion';

    public static function form(Schema $schema): Schema
    {
        return UbicacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UbicacionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUbicacions::route('/'),
            'create' => CreateUbicacion::route('/create'),
            'edit' => EditUbicacion::route('/{record}/edit'),
        ];
    }
}
