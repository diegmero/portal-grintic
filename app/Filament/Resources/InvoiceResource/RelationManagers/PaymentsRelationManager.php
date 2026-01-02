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
        return false;
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
                    ->maxValue(fn () => $this->getOwnerRecord()->total),
                
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Fecha de Pago')
                    ->default(now())
                    ->required()
                    ->maxDate(now()),
                
                Forms\Components\Select::make('payment_method')
                    ->label('Método de Pago')
                    ->options(PaymentMethod::class)
                    ->required(),
                
                Forms\Components\TextInput::make('transaction_reference')
                    ->label('Referencia/Nº Transacción')
                    ->maxLength(255),
                
                Forms\Components\FileUpload::make('attachment_path')
                    ->label('Comprobante')
                    ->directory('payments')
                    ->visibility('private')
                    ->acceptedFileTypes(['image/*', 'application/pdf']),
                
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpan('full'),
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
                    ->afterFormFilled(function () {
                        // Pre-llenar con el monto pendiente
                        $invoice = $this->getOwnerRecord();
                        $paid = $invoice->payments()->sum('amount');
                        $remaining = $invoice->total - $paid;
                        return ['amount' => $remaining > 0 ? $remaining : 0];
                    })
                    ->after(function ($record) {
                        $invoice = $this->getOwnerRecord();
                        
                        // Si la factura está completamente pagada
                        if ($invoice->isPaid()) {
                            $invoice->markAsPaid();
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('¡Factura pagada!')
                                ->body('La factura y los períodos vinculados se han marcado como pagados.')
                                ->send();
                        }
                        
                        $this->dispatch('$refresh');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(fn () => $this->dispatch('$refresh')),
                Tables\Actions\DeleteAction::make()
                    ->after(fn () => $this->dispatch('$refresh')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(fn () => $this->dispatch('$refresh')),
            ]);
    }
}