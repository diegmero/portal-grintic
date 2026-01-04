<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestInvoices extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Últimas Facturas Generadas';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Invoice::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
            ])
            ->paginated(false)
            ->actions([]);
    }
}
