<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Livewire\Attributes\On;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;
    
    protected static string $view = 'filament.resources.invoice-resource.pages.view-invoice';

    #[On('$refresh')]
    public function refreshRecord(): void
    {
        $this->record->refresh();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn ($record) => $record->status !== \App\Enums\InvoiceStatus::PAID),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Grid::make(3)
                    ->schema([
                        Components\Section::make('Resumen de Factura')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Components\TextEntry::make('invoice_number')
                                    ->label('Número'),
                                Components\TextEntry::make('client.company_name')
                                    ->label('Cliente')
                                    ->icon('heroicon-o-building-office'),
                                Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Totales')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Components\TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('USD'),
                                Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->money('USD')
                                    ->color('success'),
                                Components\TextEntry::make('balance_due')
                                    ->label('Pendiente')
                                    ->money('USD')
                                    ->state(fn ($record) => $record->total - $record->payments()->sum('amount'))
                                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Fechas')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Components\TextEntry::make('issue_date')
                                    ->label('Fecha de Emisión')
                                    ->date('d/m/Y'),
                                Components\TextEntry::make('created_at')
                                    ->label('Hora de Emisión')
                                    ->dateTime('h:i A')
                                    ->timezone('America/Bogota'),
                                Components\TextEntry::make('due_date')
                                    ->label('Fecha de Vencimiento')
                                    ->date('d/m/Y')
                                    ->color(fn ($record) => $record->due_date < now() && $record->status !== \App\Enums\InvoiceStatus::PAID ? 'danger' : null),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\InvoiceResource\RelationManagers\InvoiceItemsRelationManager::class,
            \App\Filament\Resources\InvoiceResource\RelationManagers\PaymentsRelationManager::class,
        ];
    }
}
