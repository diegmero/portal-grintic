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
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Resumen de Factura')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Components\TextEntry::make('invoice_number')
                            ->label('Número')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Components\TextEntry::make('client.company_name')
                            ->label('Cliente')
                            ->icon('heroicon-o-building-office'),
                        Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge(),
                    ])
                    ->columns(1),
                
                Components\Section::make('Totales')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('USD'),
                        Components\TextEntry::make('tax_amount')
                            ->label('Impuestos')
                            ->money('USD')
                            ->visible(fn ($record) => $record->tax_amount > 0),
                        Components\TextEntry::make('total')
                            ->label('Total')
                            ->money('USD')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),
                    ])
                    ->columns(1),
                
                Components\Section::make('Fechas')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Components\TextEntry::make('issue_date')
                            ->label('Fecha de Emisión')
                            ->date('d/m/Y'),
                        Components\TextEntry::make('due_date')
                            ->label('Fecha de Vencimiento')
                            ->date('d/m/Y')
                            ->color(fn ($record) => $record->due_date < now() && $record->status !== \App\Enums\InvoiceStatus::PAID ? 'danger' : null),
                    ])
                    ->columns(1),
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
