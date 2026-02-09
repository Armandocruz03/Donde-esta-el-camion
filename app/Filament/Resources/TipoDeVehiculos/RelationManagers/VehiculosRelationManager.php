<?php

namespace App\Filament\Resources\TipoDeVehiculos\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiculosRelationManager extends RelationManager
{
    protected static string $relationship = 'vehiculos'; 

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('placas')
                    ->required()
                    ->maxLength(255),

                TextInput::make('modelo')->required(),
                TextInput::make('color')->required(),
                TextInput::make('dimensiones')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('placas')
            ->columns([
                TextColumn::make('placas')->searchable(),
                TextColumn::make('modelo'),
                TextColumn::make('color'),
                TextColumn::make('dimensiones'),
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
