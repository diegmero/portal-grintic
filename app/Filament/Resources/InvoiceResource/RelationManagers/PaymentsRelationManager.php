<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Enums\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    
    protected static ?string $title = 'Pagos';

    public function isReadOnly(): bool
    {
        // Read-only si la factura está pagada
        return $this->getOwnerRecord()->status === \App\Enums\InvoiceStatus::PAID;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->minValue(0.01)
                    ->disabled()
                    ->maxValue(function ($record) {
                        $invoice = $this->getOwnerRecord();
                        $alreadyPaid = $invoice->payments()
                            ->where('id', '!=', $record?->id)
                            ->sum('amount');
                        
                        return max(0, round($invoice->total - $alreadyPaid, 2));
                    }),
                
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Fecha de Pago')
                    ->default(now())
                    ->required()
                    ->maxDate(now())
                    ->disabled(),
                
                Forms\Components\Select::make('payment_method')
                    ->label('Método de Pago')
                    ->options(PaymentMethod::class)
                    ->required()
                    ->disabled(),
                
                Forms\Components\TextInput::make('transaction_reference')
                    ->label('Referencia/Nº Transacción')
                    ->maxLength(255)
                    ->disabled(),
                
                Forms\Components\FileUpload::make('attachment_path')
                    ->label('Comprobante')
                    ->directory('payments')
                    ->visibility('private')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->disabled(),
                
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpan('full')
                    ->helperText('Este es el único campo editable'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('transaction_reference')
                    ->label('Referencia')
                    ->limit(20),
                
                Tables\Columns\TextColumn::make('attachment_path')
                    ->label('Comprobante')
                    ->formatStateUsing(fn ($state) => $state ? '📎 Ver Adjunto' : '-')
                    ->color(fn ($state) => $state ? 'info' : 'gray')
                    ->action(
                        Tables\Actions\Action::make('preview')
                            ->visible(fn ($record) => !empty($record->attachment_path))
                            ->modalContent(fn ($record) => view('filament.components.file-preview', [
                                'url' => route('files.payments.view', ['filename' => basename($record->attachment_path)]),
                                'type' => \Illuminate\Support\Str::endsWith($record->attachment_path, '.pdf') ? 'pdf' : 'image',
                            ]))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(fn ($action) => $action->label('Cerrar'))
                            ->modalWidth('5xl')
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Pago')
                    ->visible(fn () => $this->getOwnerRecord()->status !== \App\Enums\InvoiceStatus::PAID)
                    ->afterFormFilled(function () {
                        // Pre-llenar con el monto pendiente
                        $invoice = $this->getOwnerRecord();
                        $paid = $invoice->payments()->sum('amount');
                        $remaining = $invoice->total - $paid;
                        return ['amount' => $remaining > 0 ? $remaining : 0];
                    })
                    ->after(function ($record) {
                        $invoice = $this->getOwnerRecord();
                        
                        // Recalcular estado de la factura
                        $invoice->calculateTotals();
                        
                        $this->dispatch('$refresh');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => $this->getOwnerRecord()->status !== \App\Enums\InvoiceStatus::PAID)
                    ->after(function () {
                        $this->getOwnerRecord()->calculateTotals();
                        $this->dispatch('$refresh');
                    }),
            ]);
    }
}