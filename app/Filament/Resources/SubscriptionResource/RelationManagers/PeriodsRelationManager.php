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

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Período')
                    ->schema([
                        Forms\Components\DatePicker::make('period_start')
                            ->label('Inicio del Período')
                            ->required()
                            ->disabled(fn ($record) => $record && $record->invoice_id),
                        
                        Forms\Components\DatePicker::make('period_end')
                            ->label('Fin del Período')
                            ->required()
                            ->after('period_start')
                            ->disabled(fn ($record) => $record && $record->invoice_id),
                        
                        Forms\Components\Placeholder::make('amount_display')
                            ->label('Monto del Período')
                            ->content(fn ($livewire) => '$' . number_format($livewire->ownerRecord->effective_price, 2))
                            ->helperText('Este monto se toma automáticamente del precio de la suscripción'),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(collect(SubscriptionPeriodStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])->toArray())
                            ->default(SubscriptionPeriodStatus::PENDING)
                            ->required()
                            ->disabled(fn ($record) => $record && $record->invoice_id),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Descripción del Trabajo')
                    ->schema([
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Informe / Adjuntos')
                            ->directory('subscription-period-attachments')
                            ->multiple()
                            ->maxFiles(10)
                            ->maxSize(10240)
                            ->acceptedFileTypes(['application/pdf'])
                            ->columnSpan('full')
                            ->downloadable()
                            ->openable()
                            ->hint('Máximo 10 archivos PDF, 10MB c/u'),

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
            ->recordAction('edit')
            ->columns([
                Tables\Columns\TextColumn::make('period_label')
                    ->label('Período')
                    ->weight('bold')
                    ->sortable(['period_start']),
                
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
                

                Tables\Columns\TextColumn::make('attachments')
                    ->label('Adjuntos')
                    ->formatStateUsing(function ($state, $record) {
                        return !empty($record->attachments) ? '📎' : '';
                    })
                    ->badge(false)
                    ->color('info')
                    ->alignCenter()
                    ->tooltip(fn ($record) => !empty($record->attachments) ? count($record->attachments) . ' archivo(s)' : null)
                    ->action(
                        Tables\Actions\Action::make('previewAttachments')
                            ->visible(fn ($record) => !empty($record->attachments))
                            ->modalHeading('Archivos Adjuntos')
                            ->modalWidth('5xl')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(fn ($action) => $action->label('Cerrar'))
                            ->modalContent(fn ($record) => view('filament.components.subscription-period-attachments-preview', [
                                'attachments' => $record->attachments,
                            ]))
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(collect(SubscriptionPeriodStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])->toArray()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Período')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['amount'] = $this->ownerRecord->effective_price;
                        return $data;
                    }),
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
                                'status' => \App\Enums\InvoiceStatus::INVOICED,
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
                            
                            return redirect()->route('filament.admin.resources.invoices.view', ['record' => $invoice]);
                        });
                    })
                    ->requiresConfirmation(),
                
                Tables\Actions\Action::make('bill_and_pay')
                    ->label('Factura y Pago')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === SubscriptionPeriodStatus::PENDING)
                    ->form([
                        Forms\Components\Textarea::make('work_description')
                            ->label('Descripción del Trabajo')
                            ->hint('Opcional')
                            ->rows(3),
                        
                        Forms\Components\Section::make('Datos del Pago')
                            ->schema([
                                Forms\Components\DatePicker::make('payment_date')
                                    ->label('Fecha de Pago')
                                    ->default(now())
                                    ->required(),
                                
                                Forms\Components\Select::make('payment_method')
                                    ->label('Método de Pago')
                                    ->options(\App\Enums\PaymentMethod::class)
                                    ->required(),
                                
                                Forms\Components\TextInput::make('transaction_reference')
                                    ->label('Referencia/Nº Transacción'),
                            ])
                            ->columns(3),
                    ])
                    ->action(function ($record, array $data) {
                        return \DB::transaction(function () use ($record, $data) {
                            // 1. Crear factura
                            $invoice = \App\Models\Invoice::create([
                                'client_id' => $record->subscription->client_id,
                                'issue_date' => now(),
                                'due_date' => now(),
                                'subtotal' => 0,
                                'tax_percentage' => 0,
                                'tax_amount' => 0,
                                'total' => 0,
                                'status' => \App\Enums\InvoiceStatus::INVOICED,
                            ]);
                            
                            // 2. Agregar item
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
                            
                            // 3. Calcular totales
                            $invoice->calculateTotals();
                            
                            // 4. Vincular período a factura
                            $record->invoice_id = $invoice->id;
                            if (!empty($data['work_description'])) {
                                $record->work_description = $data['work_description'];
                            }
                            $record->save();
                            
                            // 5. Registrar pago
                            $invoice->payments()->create([
                                'amount' => $invoice->total,
                                'payment_date' => $data['payment_date'],
                                'payment_method' => $data['payment_method'],
                                'transaction_reference' => $data['transaction_reference'] ?? null,
                            ]);
                            
                            // 6. Marcar factura y período como pagados
                            $invoice->markAsPaid();
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('¡Cobro registrado!')
                                ->body("Factura {$invoice->invoice_number} creada y pagada")
                                ->send();
                        });
                    }),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !$record->invoice_id)
                    ->tooltip(fn ($record) => $record->invoice_id ? 'No se puede eliminar: tiene factura vinculada' : null)
                    ->before(function ($record, $action) {
                        if ($record->status !== \App\Enums\SubscriptionPeriodStatus::PENDING || $record->invoice_id) {
                                \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Operación Bloqueada')
                                ->body('No se puede eliminar un período que ya ha sido facturado o pagado. Debes anular la factura primero.')
                                ->send();
                            $action->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records, $action) {
                            $hasInvoiced = $records->filter(fn ($r) => $r->invoice_id || $r->status !== \App\Enums\SubscriptionPeriodStatus::PENDING)->count();
                            if ($hasInvoiced > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Operación Bloqueada')
                                    ->body("No puedes eliminar registros que ya han sido facturados o pagados.")
                                    ->send();
                                $action->halt();
                            }
                        }),
                ]),
            ]);
    }
}
