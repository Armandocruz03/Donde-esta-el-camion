<?php

namespace App\Filament\Resources\TipoDeVehiculos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;


class TipoDeVehiculosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SelectColumn::make('capacidad')
                  ->options([
        'Camion 2T' => 'Camion 2T',
        'Camioneta de redilas 1T' => 'Camioneta de redilas 1T',
        'Carro pequeño 100kg' => 'Carro pequeño 100kg',
    ])
                    ->searchable(),
                TextColumn::make('marca')
                    ->searchable(),
                TextColumn::make('modelo')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
