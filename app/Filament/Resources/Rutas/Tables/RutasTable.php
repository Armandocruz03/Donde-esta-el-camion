<?php

namespace App\Filament\Resources\Rutas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;

class RutasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                IconColumn::make('activa')
                    ->boolean(),
                TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'success' => 'en_ruta',
                        'danger' => 'detenida',
                    ]),
                TextColumn::make('segundos_avance')
                    ->label('Segundos Avance')
                    ->sortable(),
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

    ViewAction::make(),
    EditAction::make(),

    Action::make('iniciar')
        ->label('Iniciar')
        ->icon('heroicon-o-play')
        ->color('success')
        ->visible(fn ($record) => $record->estado !== 'en_ruta')
        ->action(function ($record) {

            $record->estado = 'en_ruta';
            $record->punto_actual = 0;
            $record->ultima_actualizacion = now();
            $record->save();

        }),
        

   Action::make('detener')
        ->label('Detener')
        ->icon('heroicon-o-stop')
        ->color('danger')
        ->visible(fn ($record) => $record->estado === 'en_ruta')
        ->action(function ($record) {

            $record->estado = 'detenida';
            $record->save();

        }),

])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
