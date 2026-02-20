<?php

namespace App\Filament\Resources\Vehiculos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ColorColumn;


class VehiculosTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
