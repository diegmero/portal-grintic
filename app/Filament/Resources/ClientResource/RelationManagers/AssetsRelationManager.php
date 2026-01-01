<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Enums\AssetType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'assets';
    
    protected static ?string $title = 'Activos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
                
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->maxLength(500),
                
                Forms\Components\Select::make('technology')
                    ->label('Tecnología')
                    ->options(AssetType::class)
                    ->required(),
                
                Forms\Components\Textarea::make('credentials')
                    ->label('Credenciales')
                    ->hint('Se encriptarán automáticamente')
                    ->rows(3)
                    ->columnSpan('full'),
                
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpan('full'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('technology')
                    ->label('Tecnología')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(30)
                    ->url(fn ($record) => $record->url, true),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}