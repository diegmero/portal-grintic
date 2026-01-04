<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'invoiceItems';
    
    protected static ?string $title = 'Items de Factura';

    public function isReadOnly(): bool
    {
        // Read-only si la factura está pagada
        return $this->getOwnerRecord()->status === \App\Enums\InvoiceStatus::PAID;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(500)
                    ->columnSpan('full'),
                
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(0.01)
                    ->step(0.01)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $qty = (float) $state;
                        $price = (float) $get('unit_price');
                        $set('subtotal', $qty * $price);
                    }),
                
                Forms\Components\TextInput::make('unit_price')
                    ->label('Precio Unitario')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $qty = (float) $get('quantity');
                        $price = (float) $state;
                        $set('subtotal', $qty * $price);
                    }),
                
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('$')
                    ->disabled()
                    ->dehydrated(),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad'),
                
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Precio Unit.')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('USD'),
            ])
            ->recordUrl(function ($record) {
                if (!$record->itemable) return null;
                
                return match($record->itemable_type) {
                    'App\Models\Project' => \App\Filament\Resources\ProjectResource::getUrl('view', ['record' => $record->itemable]),
                    'App\Models\WorkLog' => \App\Filament\Resources\WorkLogResource::getUrl('view', ['record' => $record->itemable]),
                    'App\Models\SubscriptionPeriod' => \App\Filament\Resources\SubscriptionResource::getUrl('view', ['record' => $record->itemable->subscription]), 
                    default => null,
                };
            })
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Item')
                    ->visible(fn () => $this->getOwnerRecord()->status !== \App\Enums\InvoiceStatus::PAID)
                    ->after(function () {
                        $this->getOwnerRecord()->calculateTotals();
                        $this->dispatch('$refresh');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => $this->getOwnerRecord()->status !== \App\Enums\InvoiceStatus::PAID)
                    ->after(function () {
                        $this->getOwnerRecord()->calculateTotals();
                        $this->dispatch('$refresh');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => $this->getOwnerRecord()->status !== \App\Enums\InvoiceStatus::PAID)
                    ->after(function () {
                        $this->getOwnerRecord()->calculateTotals();
                        $this->dispatch('$refresh');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => $this->getOwnerRecord()->status !== \App\Enums\InvoiceStatus::PAID)
                    ->after(function () {
                        $this->getOwnerRecord()->calculateTotals();
                        $this->dispatch('$refresh');
                    }),
            ]);
    }
}