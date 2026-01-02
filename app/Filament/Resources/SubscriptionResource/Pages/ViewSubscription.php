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
                Components\Section::make('Información de la Suscripción')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Components\TextEntry::make('service.name')
                            ->label('Servicio')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Components\TextEntry::make('client.company_name')
                            ->label('Cliente')
                            ->icon('heroicon-o-building-office'),
                        Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge(),
                        Components\TextEntry::make('billing_cycle')
                            ->label('Ciclo de Facturación')
                            ->placeholder('No definido'),
                    ])
                    ->columns(1),
                
                Components\Section::make('Precio')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Components\TextEntry::make('effective_price')
                            ->label('Precio Actual')
                            ->money('USD')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),
                        Components\TextEntry::make('service.base_price')
                            ->label('Precio Base del Servicio')
                            ->money('USD')
                            ->color('gray'),
                        Components\TextEntry::make('custom_price')
                            ->label('Precio Personalizado')
                            ->money('USD')
                            ->placeholder('Usa precio base'),
                    ])
                    ->columns(1),
                
                Components\Section::make('Fechas')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Components\TextEntry::make('started_at')
                            ->label('Inicio')
                            ->date('d/m/Y')
                            ->placeholder('No definida'),
                        Components\TextEntry::make('next_billing_date')
                            ->label('Próxima Facturación')
                            ->date('d/m/Y')
                            ->placeholder('No definida'),
                        Components\TextEntry::make('cancelled_at')
                            ->label('Cancelada')
                            ->date('d/m/Y')
                            ->placeholder('Activa')
                            ->color('danger'),
                    ])
                    ->columns(1),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\SubscriptionResource\RelationManagers\PeriodsRelationManager::class,
        ];
    }
}
