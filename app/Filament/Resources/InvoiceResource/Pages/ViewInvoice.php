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

    // public function infolist(Infolist $infolist): Infolist
    // {
    //     return parent::infolist($infolist);
    // }

    // public function getRelationManagers(): array
    // {
    //     return [];
    // }
}
