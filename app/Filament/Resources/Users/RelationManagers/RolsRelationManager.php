<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolsRelationManager extends RelationManager
{
    protected static string $relationship = 'rols';

    protected static ?string $title = 'Roles';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Rol')
                    ->searchable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Asignar Rol'),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Quitar Rol'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->label('Quitar Seleccionados'),
                ]),
            ]);
    }
}
