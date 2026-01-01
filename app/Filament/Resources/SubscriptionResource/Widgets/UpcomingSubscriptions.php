<?php

namespace App\Filament\Resources\SubscriptionResource\Widgets;

use App\Models\Subscription;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingSubscriptions extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Subscription::query()
                    ->where('status', 'active')
                    ->whereDate('next_billing_date', '<=', now()->addDays(30))
                    ->orderBy('next_billing_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente'),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio'),
                Tables\Columns\TextColumn::make('next_billing_date')
                    ->label('Próxima Facturación')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record->next_billing_date <= now()->addDays(7) ? 'danger' : 'warning'),
                Tables\Columns\TextColumn::make('effective_price')
                    ->label('Monto')
                    ->money('USD'),
            ])
            ->heading('Renovaciones Próximas (30 días)');
    }
}