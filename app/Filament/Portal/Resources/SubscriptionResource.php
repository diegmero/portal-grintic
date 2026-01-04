<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\SubscriptionResource\Pages;
use App\Filament\Portal\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Infolist;
use Filament\Infolists;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Facturación';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'id')
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required(),
                Forms\Components\TextInput::make('custom_price')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('billing_cycle')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('started_at')
                    ->required(),
                Forms\Components\DatePicker::make('cancelled_at'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('active'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información de Suscripción')
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label('Servicio'),
                        Infolists\Components\TextEntry::make('billing_cycle')
                            ->label('Ciclo de Facturación')
                            ->badge(),
                        Infolists\Components\TextEntry::make('effective_price')
                            ->label('Precio')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge(),
                        Infolists\Components\TextEntry::make('started_at')
                            ->label('Fecha de Inicio')
                            ->date('d/m/Y'),
                        Infolists\Components\TextEntry::make('cancelled_at')
                            ->label('Fecha de Cancelación')
                            ->date('d/m/Y')
                            ->visible(fn ($record) => $record->cancelled_at),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->label('Ciclo')
                    ->badge(),
                Tables\Columns\TextColumn::make('effective_price')
                    ->money('USD')
                    ->label('Precio'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('started_at')
                    ->date()
                    ->label('Inicio'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PeriodsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('client_id', auth()->user()->client_id);
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'view' => Pages\ViewSubscription::route('/{record}'),
        ];
    }
}
