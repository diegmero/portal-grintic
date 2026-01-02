<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;
    
    protected static string $view = 'filament.resources.invoice-resource.pages.edit-invoice';

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Proteger facturas pagadas: redirigir a View
        if ($this->record->status === \App\Enums\InvoiceStatus::PAID) {
            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('Factura pagada')
                ->body('Las facturas pagadas no pueden ser editadas.')
                ->send();
            
            $this->redirect(InvoiceResource::getUrl('view', ['record' => $this->record]));
        }
    }

    #[\Livewire\Attributes\On('$refresh')]
    public function refreshRecord(): void
    {
        $this->record->refresh();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn ($record) => !$record->hasLinkedPeriods()),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Components\Section::make('Resumen')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Components\TextEntry::make('invoice_number')
                            ->label('Factura')
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
                        Components\TextEntry::make('tax_percentage')
                            ->label('% Impuesto')
                            ->suffix('%')
                            ->visible(fn ($record) => $record->tax_percentage > 0),
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
                
                Components\Section::make('Pagos')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Components\TextEntry::make('payments_sum')
                            ->label('Total Pagado')
                            ->getStateUsing(fn ($record) => $record->payments()->sum('amount'))
                            ->money('USD')
                            ->color('info'),
                        Components\TextEntry::make('balance')
                            ->label('Saldo Pendiente')
                            ->getStateUsing(fn ($record) => $record->total - $record->payments()->sum('amount'))
                            ->money('USD')
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                    ])
                    ->columns(1),
            ]);
    }

}

