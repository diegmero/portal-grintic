<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\ServiceResource\Pages;
use App\Filament\Portal\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Servicio';
    protected static ?string $pluralModelLabel = 'Servicios';
    protected static ?string $navigationLabel = 'Catálogo de Servicios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Servicio')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->label('Tipo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('base_price')
                    ->label('Precio Base')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Activo')
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detalles del Servicio')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Servicio')
                            ->weight('bold'),
                        
                        Infolists\Components\TextEntry::make('type')
                            ->label('Tipo')
                            ->badge(),
                            
                        Infolists\Components\TextEntry::make('base_price')
                            ->label('Precio Base')
                            ->money('USD'),
                            
                        Infolists\Components\TextEntry::make('is_active')
                            ->label('Estado')
                            ->badge()
                            ->formatStateUsing(fn (bool $state) => $state ? 'Disponible' : 'No disponible')
                            ->color(fn (bool $state) => $state ? 'success' : 'danger'),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('features')
                            ->label('Características Destacadas')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->columnSpanFull(),

                        Infolists\Components\KeyValueEntry::make('pricing')
                            ->label('Precios por Ciclo')
                            ->keyLabel('Ciclo')
                            ->valueLabel('Precio')
                            ->columnSpanFull(),
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
                    ->wrap(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Precio Base')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Disponible')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver Detalles')
                    ->modalWidth('lg'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageServices::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_active', true);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
