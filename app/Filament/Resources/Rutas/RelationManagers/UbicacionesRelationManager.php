<?php

namespace App\Filament\Resources\Rutas\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class UbicacionsRelationManager extends RelationManager
{
    protected static string $relationship = 'ubicaciones';
    protected static ?string $title = 'Ubicacions';

    protected static ?string $recordTitleAttribute = 'nombre';

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('orden')
                ->numeric()
                ->required()
                ->default(fn () =>
                    ($this->getOwnerRecord()
                        ->ubicaciones()
                        ->max('orden') ?? 0) + 1
                ),

            TextInput::make('nombre')
                ->required()
                ->maxLength(255),

            TextInput::make('lat')
                ->numeric()
                ->required(),

            TextInput::make('lng')
                ->numeric()
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('orden', 'asc')
            ->columns([
                TextColumn::make('orden')->sortable(),
                TextColumn::make('nombre')->searchable(),
                TextColumn::make('lat'),
                TextColumn::make('lng'),
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
