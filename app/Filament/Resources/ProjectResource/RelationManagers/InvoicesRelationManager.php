<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\ProjectStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';
    
    protected static ?string $title = 'Facturas';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Sin facturas')
            ->emptyStateDescription('Este proyecto no tiene facturas asociadas.')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Número'),
                
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Fecha')
                    ->date('d/m/Y'),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'draft' => 'Borrador',
                        'sent' => 'Enviada',
                        'paid' => 'Pagada',
                        'overdue' => 'Vencida',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create_invoice')
                    ->label('Crear Factura')
                    ->icon('heroicon-o-document-plus')
                    ->color('success')
                    ->visible(fn () => !$this->getOwnerRecord()->invoiceItems()->exists())
                    ->requiresConfirmation()
                    ->modalHeading('Crear Factura')
                    ->modalDescription('¿Desea crear una factura con el presupuesto de este proyecto?')
                    ->modalSubmitActionLabel('Sí, crear factura')
                    ->modalCancelActionLabel('Cancelar')
                    ->action(function () {
                        $project = $this->getOwnerRecord();
                        
                        return \DB::transaction(function () use ($project) {
                            $invoice = \App\Models\Invoice::create([
                                'client_id' => $project->client_id,
                                'issue_date' => now(),
                                'due_date' => now()->addDays(30),
                                'subtotal' => 0,
                                'tax_percentage' => 0,
                                'tax_amount' => 0,
                                'total' => 0,
                                'status' => 'draft',
                            ]);
                            
                            $description = "Proyecto: {$project->name}";
                            if ($project->description) {
                                $description .= " - {$project->description}";
                            }
                            
                            $invoice->invoiceItems()->create([
                                'description' => $description,
                                'quantity' => 1,
                                'unit_price' => $project->total_budget,
                                'subtotal' => $project->total_budget,
                                'itemable_type' => \App\Models\Project::class,
                                'itemable_id' => $project->id,
                            ]);
                            
                            $invoice->calculateTotals();
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Factura creada')
                                ->body("Factura {$invoice->invoice_number} generada exitosamente")
                                ->send();
                            
                            return redirect()->route('filament.admin.resources.invoices.edit', ['record' => $invoice]);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.invoices.edit', ['record' => $record])),
            ]);
    }
}
