<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;
    
    protected static string $view = 'filament.resources.client-resource.pages.view-client';

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
                        Components\Section::make('Información del Cliente')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Components\TextEntry::make('company_name')
                                    ->label('Empresa'),
                                Components\TextEntry::make('country_name')
                                    ->label('País')
                                    ->placeholder('No definido'),
                                Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                        
                        Components\Section::make('Métricas')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Components\TextEntry::make('contacts_count')
                                    ->label('Contactos')
                                    ->state(fn ($record) => $record->contacts()->count())
                                    ->icon('heroicon-o-user-group')
                                    ->color('info'),
                                Components\TextEntry::make('subscriptions_count')
                                    ->label('Suscripciones Activas')
                                    ->state(fn ($record) => $record->subscriptions()->where('status', 'active')->count())
                                    ->icon('heroicon-o-arrow-path')
                                    ->color('warning'),
                                Components\TextEntry::make('projects_count')
                                    ->label('Proyectos')
                                    ->state(fn ($record) => $record->projects()->count())
                                    ->icon('heroicon-o-folder')
                                    ->color('success'),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                        
                        Components\Section::make('Información Financiera')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Components\TextEntry::make('total_invoiced')
                                    ->label('Total Facturado')
                                    ->state(fn ($record) => $record->invoices()->sum('total'))
                                    ->money('USD')
                                    ->icon('heroicon-o-document-text'),
                                Components\TextEntry::make('pending_balance')
                                    ->label('Pendiente de Pago')
                                    ->state(function ($record) {
                                        $unpaidInvoices = $record->invoices()
                                            ->whereIn('status', ['sent', 'overdue', 'partially_paid'])
                                            ->get();
                                        
                                        $pendingTotal = 0;
                                        foreach ($unpaidInvoices as $invoice) {
                                            $paid = $invoice->payments()->sum('amount');
                                            $pendingTotal += ($invoice->total - $paid);
                                        }
                                        
                                        return $pendingTotal;
                                    })
                                    ->money('USD')
                                    ->icon('heroicon-o-clock')
                                    ->color('warning'),
                                Components\TextEntry::make('invoices_count')
                                    ->label('Facturas')
                                    ->state(fn ($record) => $record->invoices()->count())
                                    ->icon('heroicon-o-document-duplicate'),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\ClientResource\RelationManagers\ContactsRelationManager::class,
            \App\Filament\Resources\ClientResource\RelationManagers\WorkLogsRelationManager::class,
            \App\Filament\Resources\ClientResource\RelationManagers\ClientNotesRelationManager::class,
            \App\Filament\Resources\ClientResource\RelationManagers\SubscriptionsRelationManager::class,
            \App\Filament\Resources\ClientResource\RelationManagers\ProjectsRelationManager::class,
        ];
    }
}
