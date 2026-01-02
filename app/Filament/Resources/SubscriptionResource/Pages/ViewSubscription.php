<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;
    
    protected static string $view = 'filament.resources.subscription-resource.pages.view-subscription';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Grid::make(3)
                    ->schema([
                        Components\Section::make('Información de la Suscripción')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Components\TextEntry::make('service.name')
                                    ->label('Servicio'),
                                Components\TextEntry::make('client.company_name')
                                    ->label('Cliente'),
                                Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Precio y Ciclo')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Components\TextEntry::make('effective_price')
                                    ->label('Precio Actual')
                                    ->money('USD')
                                    ->color('success'),
                                Components\TextEntry::make('billing_cycle')
                                    ->label('Ciclo de Facturación')
                                    ->placeholder('No definido'),
                                Components\TextEntry::make('custom_price')
                                    ->label('Precio Personalizado')
                                    ->money('USD')
                                    ->placeholder('Usa precio base'),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Fechas')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Components\TextEntry::make('started_at')
                                    ->label('Inicio')
                                    ->date('d/m/Y')
                                    ->placeholder('No definida'),
                                Components\TextEntry::make('last_payment_date')
                                    ->label('Último Pago')
                                    ->state(function ($record) {
                                        $lastPayment = $record->periods()
                                            ->whereHas('invoice.payments')
                                            ->with(['invoice.payments' => fn($q) => $q->latest('payment_date')])
                                            ->get()
                                            ->flatMap(fn($period) => $period->invoice?->payments ?? collect())
                                            ->sortByDesc('payment_date')
                                            ->first();
                                        
                                        return $lastPayment?->payment_date?->format('d/m/Y') ?? 'Sin pagos';
                                    }),
                                Components\TextEntry::make('periods_count')
                                    ->label('Períodos Generados')
                                    ->state(fn ($record) => $record->periods()->count()),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\SubscriptionResource\RelationManagers\PeriodsRelationManager::class,
        ];
    }
}
