<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('bill')
                ->label('Facturar Proyecto')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->visible(fn () => 
                    $this->record->status === ProjectStatus::DONE && 
                    !$this->record->invoiceItems()->exists()
                )
                ->requiresConfirmation()
                ->action(function () {
                    return \DB::transaction(function () {
                        $invoice = \App\Models\Invoice::create([
                            'client_id' => $this->record->client_id,
                            'issue_date' => now(),
                            'due_date' => now()->addDays(30),
                            'subtotal' => 0,
                            'tax_percentage' => 0,
                            'tax_amount' => 0,
                            'total' => 0,
                            'status' => 'draft',
                        ]);
                        
                        $description = "Proyecto: {$this->record->name}";
                        if ($this->record->description) {
                            $description .= " - {$this->record->description}";
                        }
                        
                        $invoice->invoiceItems()->create([
                            'description' => $description,
                            'quantity' => 1,
                            'unit_price' => $this->record->total_budget,
                            'subtotal' => $this->record->total_budget,
                            'itemable_type' => \App\Models\Project::class,
                            'itemable_id' => $this->record->id,
                        ]);
                        
                        $invoice->calculateTotals();
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Factura generada')
                            ->body("Factura {$invoice->invoice_number} creada")
                            ->send();
                        
                        return redirect()->route('filament.admin.resources.invoices.edit', ['record' => $invoice]);
                    });
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Resumen del Proyecto')
                    ->schema([
                        Components\Grid::make(4)
                            ->schema([
                                Components\TextEntry::make('progress')
                                    ->label('Progreso')
                                    ->state(fn ($record) => $record->progress . '%')
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->color(fn ($record) => match(true) {
                                        $record->progress >= 100 => 'success',
                                        $record->progress >= 50 => 'warning',
                                        default => 'gray',
                                    }),
                                
                                Components\TextEntry::make('total_budget')
                                    ->label('Presupuesto')
                                    ->money('USD')
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                                
                                Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                                
                                Components\TextEntry::make('days_until_deadline')
                                    ->label('Días Restantes')
                                    ->state(function ($record) {
                                        if (!$record->deadline) return 'Sin fecha límite';
                                        $days = $record->days_until_deadline;
                                        if ($days < 0) return abs($days) . ' días vencido';
                                        if ($days === 0) return 'Vence hoy';
                                        return $days . ' días';
                                    })
                                    ->color(fn ($record) => match(true) {
                                        !$record->deadline => 'gray',
                                        $record->is_overdue => 'danger',
                                        $record->days_until_deadline <= 7 => 'warning',
                                        default => 'success',
                                    })
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                            ]),
                    ]),
                
                Components\Section::make('Información')
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('client.company_name')
                                    ->label('Cliente'),
                                
                                Components\TextEntry::make('name')
                                    ->label('Nombre'),
                                
                                Components\TextEntry::make('started_at')
                                    ->label('Fecha Inicio')
                                    ->date('d/m/Y'),
                                
                                Components\TextEntry::make('deadline')
                                    ->label('Fecha Límite')
                                    ->date('d/m/Y')
                                    ->placeholder('No definida'),
                                
                                Components\TextEntry::make('description')
                                    ->label('Descripción')
                                    ->columnSpan(2),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\ProjectResource\RelationManagers\TasksRelationManager::class,
            \App\Filament\Resources\ProjectResource\RelationManagers\NotesRelationManager::class,
            \App\Filament\Resources\ProjectResource\RelationManagers\LinksRelationManager::class,
        ];
    }
}
