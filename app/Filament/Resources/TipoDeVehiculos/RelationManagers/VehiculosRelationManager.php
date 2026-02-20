<?php

namespace App\Filament\Resources\TipoDeVehiculos\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Models\User;

class VehiculosRelationManager extends RelationManager
{
    protected static string $relationship = 'vehiculos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('placas')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('anio')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(now()->year),

                TextInput::make('color')
                    ->nullable()
                    ->maxLength(100),

                Select::make('user_id')
                    ->label('Conductor')
                    ->options(
                        User::whereHas('rol', function ($q) {
                            $q->where('nombre', 'conductor');
                        })->pluck('name', 'id')
                    )
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('placas')
            ->columns([
                TextColumn::make('placas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('anio')
                    ->sortable(),

                TextColumn::make('color'),

                TextColumn::make('conductor.name')
                    ->label('Conductor')
                    ->placeholder('Sin asignar'),
                
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}