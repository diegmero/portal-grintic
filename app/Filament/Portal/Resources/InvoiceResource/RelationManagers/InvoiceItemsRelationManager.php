<?php

namespace App\Filament\Portal\Resources\InvoiceResource\RelationManagers;

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
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpan('full'),
                
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric(),
                
                Forms\Components\TextInput::make('unit_price')
                    ->label('Precio Unitario')
                    ->numeric()
                    ->prefix('$'),
                
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('$'),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
                
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
                    'App\Models\Project' => \App\Filament\Portal\Resources\ProjectResource::getUrl('view', ['record' => $record->itemable]),
                    'App\Models\WorkLog' => null, // WorkLogs are managed via modal in Portal, no separate view page
                    'App\Models\SubscriptionPeriod' => \App\Filament\Portal\Resources\SubscriptionResource::getUrl('view', ['record' => $record->itemable->subscription]), 
                    default => null,
                };
            })
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
