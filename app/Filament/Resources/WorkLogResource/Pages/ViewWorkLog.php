<?php

namespace App\Filament\Resources\WorkLogResource\Pages;

use App\Filament\Resources\WorkLogResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkLog extends ViewRecord
{
    protected static string $resource = WorkLogResource::class;
    
    protected static string $view = 'filament.resources.worklog-resource.pages.view-worklog';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn ($record) => $record->status === \App\Enums\WorkLogStatus::PENDING),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Grid::make(3)
                    ->schema([
                        Components\Section::make('Resumen del Trabajo')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                Components\TextEntry::make('service.name')
                                    ->label('Servicio'),
                                Components\TextEntry::make('worked_at')
                                    ->label('Fecha')
                                    ->date('d/m/Y'),
                                Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Económico')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Components\TextEntry::make('hours')
                                    ->label('Horas')
                                    ->suffix(' hrs'),
                                Components\TextEntry::make('hourly_rate')
                                    ->label('Tarifa/Hora')
                                    ->money('USD'),
                                Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->money('USD')
                                    ->color('success'),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Contexto')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Components\TextEntry::make('client.company_name')
                                    ->label('Cliente'),
                                Components\TextEntry::make('invoiceItem.invoice.invoice_number')
                                    ->label('Factura')
                                    ->placeholder('Sin facturar')
                                    ->url(fn ($record) => $record->invoiceItem?->invoice 
                                        ? route('filament.admin.resources.invoices.view', ['record' => $record->invoiceItem->invoice]) 
                                        : null),
                                Components\TextEntry::make('invoiceItem.invoice.status')
                                    ->label('Estado Factura')
                                    ->badge()
                                    ->visible(fn ($record) => $record->invoiceItem !== null),
                            ])
                            ->columnSpan(1),
                    ]),
                
                Components\Section::make('Descripción del Trabajo')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Components\TextEntry::make('description')
                            ->hiddenLabel()
                            ->prose()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
