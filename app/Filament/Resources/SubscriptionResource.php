<?php

namespace App\Filament\Resources;

use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    
    protected static ?string $navigationLabel = 'Suscripciones';
    
    protected static ?string $modelLabel = 'Suscripción';
    
    protected static ?string $pluralModelLabel = 'Suscripciones';
    
    protected static ?string $navigationGroup = 'Facturación';
    
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Suscripción')
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'company_name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan('full'),
                        
                        Forms\Components\Select::make('service_id')
                            ->label('Servicio')
                            ->relationship('service', 'name', function ($query) {
                                $query->where('type', 'recurring');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $service = \App\Models\Service::find($state);
                                    $set('custom_price', $service->base_price);
                                }
                            }),
                        
                        Forms\Components\TextInput::make('custom_price')
                            ->label('Precio Personalizado')
                            ->numeric()
                            ->prefix('$')
                            ->hint('Se usa el precio base del servicio si está vacío'),
                        
                        Forms\Components\Select::make('billing_cycle')
                            ->label('Ciclo de Facturación')
                            ->options(BillingCycle::class)
                            ->default(BillingCycle::MONTHLY)
                            ->required()
                            ->live(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(SubscriptionStatus::class)
                            ->default(SubscriptionStatus::ACTIVE)
                            ->required()
                            ->live(),
                        
                        Forms\Components\DatePicker::make('started_at')
                            ->label('Fecha de Inicio')
                            ->default(now())
                            ->required()
                            ->hint('Fecha en que inicia la suscripción'),
                        
                        Forms\Components\DatePicker::make('cancelled_at')
                            ->label('Fecha de Cancelación')
                            ->visible(fn (Forms\Get $get) => $get('status') === 'cancelled'),
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
                
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('effective_price')
                    ->label('Precio')
                    ->money('USD')
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderBy('custom_price', $direction);
                    }),
                
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->label('Ciclo')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('periods_count')
                    ->label('Períodos')
                    ->counts('periods')
                    ->suffix(' total')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('pending_periods_count')
                    ->label('Pendientes/Impagos')
                    ->getStateUsing(function ($record) {
                        return $record->periods()
                            ->whereIn('status', ['pending', 'invoiced'])
                            ->count();
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->formatStateUsing(fn ($state) => $state > 0 ? "$state pendiente" . ($state > 1 ? 's' : '') : 'Al día')
                    ->sortable(false),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(SubscriptionStatus::class),
                
                Tables\Filters\SelectFilter::make('billing_cycle')
                    ->label('Ciclo')
                    ->options(BillingCycle::class),
                
                Tables\Filters\Filter::make('due_soon')
                    ->label('Vence Pronto (7 días)')
                    ->query(fn ($query) => $query->dueForBilling())
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información de Suscripción')
                    ->schema([
                        Infolists\Components\TextEntry::make('client.company_name')
                            ->label('Cliente'),
                        Infolists\Components\TextEntry::make('service.name')
                            ->label('Servicio'),
                        Infolists\Components\TextEntry::make('effective_price')
                            ->label('Precio')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('billing_cycle')
                            ->label('Ciclo de Facturación')
                            ->badge(),
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

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\SubscriptionResource\RelationManagers\PeriodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
            'view' => Pages\ViewSubscription::route('/{record}'),
        ];
    }
}