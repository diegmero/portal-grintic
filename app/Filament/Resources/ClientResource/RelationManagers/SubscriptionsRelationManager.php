<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';
    
    protected static ?string $title = 'Suscripciones';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
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
                    ->hint('Deja vacío para usar el precio base del servicio'),
                
                Forms\Components\Select::make('billing_cycle')
                    ->label('Ciclo de Facturación')
                    ->options(BillingCycle::class)
                    ->default(BillingCycle::MONTHLY)
                    ->required(),
                
                Forms\Components\DatePicker::make('started_at')
                    ->label('Fecha de Inicio')
                    ->default(now())
                    ->required(),
                
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options(SubscriptionStatus::class)
                    ->default(SubscriptionStatus::ACTIVE)
                    ->required(),
                
                Forms\Components\DatePicker::make('cancelled_at')
                    ->label('Fecha de Cancelación')
                    ->visible(fn (Forms\Get $get) => $get('status') === 'cancelled'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio'),
                
                Tables\Columns\TextColumn::make('custom_price')
                    ->label('Precio')
                    ->money('USD')
                    ->default(fn ($record) => $record->effective_price),
                
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->label('Ciclo')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Inicio')
                    ->date('d/m/Y'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successRedirectUrl(fn ($record) => route('filament.admin.resources.subscriptions.edit', ['record' => $record])),
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