<?php

namespace App\Filament\Resources;

use App\Enums\AssetType;
use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    
    protected static ?string $navigationLabel = 'Activos';
    
    protected static ?string $modelLabel = 'Activo';
    
    protected static ?string $pluralModelLabel = 'Activos';
    
    protected static ?string $navigationGroup = 'CRM';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'company_name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Activo')
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
                            ->label('Credenciales (Encriptadas)')
                            ->hint('Las credenciales se encriptarán automáticamente')
                            ->rows(3)
                            ->columnSpan('full'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas Técnicas')
                            ->rows(3)
                            ->columnSpan('full'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Activo')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('technology')
                    ->label('Tecnología')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(30)
                    ->url(fn ($record) => $record->url, true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('technology')
                    ->label('Tecnología')
                    ->options(AssetType::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}