<?php

namespace App\Filament\Resources\SubscriptionResource\RelationManagers;

use App\Enums\SubscriptionPeriodStatus;
use App\Models\SubscriptionPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PeriodsRelationManager extends RelationManager
{
    protected static string $relationship = 'periods';

    protected static ?string $title = 'Períodos de Facturación';

    protected static ?string $modelLabel = 'Período';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Período')
                    ->schema([
                        Forms\Components\DatePicker::make('period_start')
                            ->label('Inicio del Período')
                            ->required(),
                        
                        Forms\Components\DatePicker::make('period_end')
                            ->label('Fin del Período')
                            ->required()
                            ->after('period_start'),
                        
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(fn ($livewire) => $livewire->ownerRecord->effective_price),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(SubscriptionPeriodStatus::class)
                            ->default(SubscriptionPeriodStatus::PENDING)
                            ->required(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Descripción del Trabajo')
                    ->schema([
                        Forms\Components\Textarea::make('work_description')
                            ->label('Descripción del Trabajo Realizado')
                            ->rows(4)
                            ->columnSpan('full')
                            ->hint('Describe el trabajo realizado en este período'),
                        
                        Forms\Components\Textarea::make('internal_notes')
                            ->label('Notas Internas')
                            ->rows(3)
                            ->columnSpan('full')
                            ->hint('Notas privadas, no visibles para el cliente'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('period_start')
            ->defaultSort('period_start', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('period_label')
                    ->label('Período')
                    ->searchable(false)
                    ->sortable(false)
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('period_start')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('period_end')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Factura')
                    ->url(fn ($record) => $record->invoice ? route('filament.admin.resources.invoices.view', $record->invoice) : null)
                    ->placeholder('Sin facturar')
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto Base')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('extras')
                    ->label('Otros')
                    ->getStateUsing(function ($record) {
                        if ($record->invoice) {
                            $extras = $record->invoice->total - $record->amount;
                            return $extras > 0 ? $extras : 0;
                        }
                        return 0;
                    })
                    ->money('USD')
                    ->placeholder('$0.00')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),
                
                Tables\Columns\TextColumn::make('invoice.total')
                    ->label('Total')
                    ->money('USD')
                    ->placeholder('-')
                    ->weight('bold')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('work_description')
                    ->label('Trabajo Realizado')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->work_description)
                    ->placeholder('Sin descripción')
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(SubscriptionPeriodStatus::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Período'),
            ])
            ->actions([
                Tables\Actions\Action::make('bill')
                    ->label('Facturar')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === SubscriptionPeriodStatus::PENDING)
                    ->form([
                        Forms\Components\Textarea::make('work_description')
                            ->label('Descripción del Trabajo')
                            ->hint('Opcional: describe el trabajo realizado')
                            ->rows(4),
                    ])
                    ->action(function ($record, array $data) {
                        return \DB::transaction(function () use ($record, $data) {
                            // Crear factura
                            $invoice = \App\Models\Invoice::create([
                                'client_id' => $record->subscription->client_id,
                                'issue_date' => now(),
                                'due_date' => now()->addDays(30),
                                'subtotal' => 0,
                                'tax_percentage' => 0,
                                'tax_amount' => 0,
                                'total' => 0,
                                'status' => 'draft',
                            ]);
                            
                            // Agregar item
                            $description = $record->subscription->service->name . " - " . $record->period_label;
                            if (!empty($data['work_description'])) {
                                $description .= "\n" . $data['work_description'];
                            }
                            
                            $invoice->invoiceItems()->create([
                                'description' => $description,
                                'quantity' => 1,
                                'unit_price' => $record->amount,
                                'subtotal' => $record->amount,
                                'itemable_type' => \App\Models\SubscriptionPeriod::class,
                                'itemable_id' => $record->id,
                            ]);
                            
                            // Actualizar período
                            $record->invoice_id = $invoice->id;
                            $record->status = SubscriptionPeriodStatus::INVOICED;
                            if (!empty($data['work_description'])) {
                                $record->work_description = $data['work_description'];
                            }
                            $record->save();
                            
                            // Calcular totales
                            $invoice->calculateTotals();
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Período facturado')
                                ->body("Factura {$invoice->invoice_number} creada")
                                ->send();
                            
                            return redirect()->route('filament.admin.resources.invoices.edit', ['record' => $invoice]);
                        });
                    })
                    ->requiresConfirmation(),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('cancel')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === SubscriptionPeriodStatus::PENDING)
                    ->action(function ($record) {
                        $record->status = SubscriptionPeriodStatus::CANCELLED;
                        $record->save();
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Período cancelado')
                            ->send();
                    })
                    ->requiresConfirmation(),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
