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
    
    protected static string $view = 'filament.resources.project-resource.pages.view-project';

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
                    !$this->record->invoiceItems()->whereHas('invoice')->exists()
                )
                ->requiresConfirmation()
                ->action(function () {
                    $invoice = \DB::transaction(function () {
                        // Crear factura en estado DRAFT inicialmente
                        $invoice = \App\Models\Invoice::create([
                            'client_id' => $this->record->client_id,
                            'issue_date' => now(),
                            'due_date' => now()->addDays(30),
                            'subtotal' => 0,
                            'tax_percentage' => 0,
                            'tax_amount' => 0,
                            'total' => 0,
                            'status' => \App\Enums\InvoiceStatus::DRAFT,
                        ]);
                        
                        // Descripción limpia sin HTML
                        $description = "Proyecto: {$this->record->name}";
                        if ($this->record->description) {
                            $cleanDescription = strip_tags($this->record->description);
                            $cleanDescription = preg_replace('/\s+/', ' ', $cleanDescription); // Múltiples espacios a uno
                            $cleanDescription = trim($cleanDescription);
                            if (strlen($cleanDescription) > 0) {
                                $description .= " - " . substr($cleanDescription, 0, 150);
                            }
                        }
                        
                        $invoice->invoiceItems()->create([
                            'description' => $description,
                            'quantity' => 1,
                            'unit_price' => $this->record->total_budget,
                            'subtotal' => $this->record->total_budget,
                            'itemable_type' => \App\Models\Project::class,
                            'itemable_id' => $this->record->id,
                        ]);
                        
                        // Refrescar para cargar items
                        $invoice->refresh();
                        
                        // Calcular totales manualmente sin cambiar estado
                        $invoice->subtotal = $invoice->invoiceItems->sum('subtotal');
                        $invoice->tax_amount = $invoice->subtotal * ($invoice->tax_percentage / 100);
                        $invoice->total = $invoice->subtotal + $invoice->tax_amount;
                        $invoice->status = \App\Enums\InvoiceStatus::INVOICED;
                        $invoice->save();
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Factura generada')
                            ->body("Factura {$invoice->invoice_number} creada con estado Enviado")
                            ->send();
                        
                        return $invoice;
                    });
                    
                    // Redirect fuera de la transacción
                    return redirect(\App\Filament\Resources\InvoiceResource::getUrl('view', ['record' => $invoice]));
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Grid::make(3)
                    ->schema([
                        Components\Section::make('Información del Proyecto')
                            ->icon('heroicon-o-folder')
                            ->schema([
                                Components\TextEntry::make('name')
                                    ->label('Nombre'),
                                Components\TextEntry::make('client.company_name')
                                    ->label('Cliente'),
                                Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Métricas')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Components\TextEntry::make('progress')
                                    ->label('Progreso')
                                    ->state(fn ($record) => $record->progress . '%')
                                    ->color(fn ($record) => match(true) {
                                        $record->progress >= 100 => 'success',
                                        $record->progress >= 50 => 'warning',
                                        default => 'gray',
                                    }),
                                Components\TextEntry::make('total_budget')
                                    ->label('Presupuesto')
                                    ->money('USD')
                                    ->color('success'),
                                Components\TextEntry::make('tasks_count')
                                    ->label('Tareas')
                                    ->state(fn ($record) => $record->tasks()->count() . ' totales'),
                            ])
                            ->columnSpan(1),
                        
                        Components\Section::make('Fechas')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Components\TextEntry::make('started_at')
                                    ->label('Inicio')
                                    ->date('d/m/Y')
                                    ->placeholder('No definida'),
                                Components\TextEntry::make('deadline')
                                    ->label('Fecha Límite')
                                    ->date('d/m/Y')
                                    ->placeholder('Sin fecha límite')
                                    ->color(fn ($record) => $record?->is_overdue ? 'danger' : null),
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
                                    }),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\ProjectResource\RelationManagers\TasksRelationManager::class,
            \App\Filament\Resources\ProjectResource\RelationManagers\DocumentationRelationManager::class,
            \App\Filament\Resources\ProjectResource\RelationManagers\ResourcesRelationManager::class,
            \App\Filament\Resources\ProjectResource\RelationManagers\InvoicesRelationManager::class,
        ];
    }
}
