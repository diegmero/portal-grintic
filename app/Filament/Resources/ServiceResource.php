<?php

namespace App\Filament\Resources;

use App\Enums\ServiceType;
use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    
    protected static ?string $navigationLabel = 'Catálogo';
    
    protected static ?string $modelLabel = 'Servicio';
    
    protected static ?string $pluralModelLabel = 'Servicios';
    
    protected static ?string $navigationGroup = 'Servicios';
    
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Servicio')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),
                        
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options(ServiceType::class)
                            ->required()
                            ->live(),
                        
                        Forms\Components\TextInput::make('base_price')
                            ->label('Precio Base')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->hint(fn (Forms\Get $get) => 
                                $get('type') === 'hourly' ? 'Precio por hora' : 'Precio mensual'
                            ),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Precio Base')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subscriptions_count')
                    ->label('Suscripciones')
                    ->counts('subscriptions')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(ServiceType::class),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}