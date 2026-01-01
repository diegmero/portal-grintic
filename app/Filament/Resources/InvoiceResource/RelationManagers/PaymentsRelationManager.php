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
                
                Tables\Columns\IconColumn::make('attachment_path')
                    ->label('Comprobante')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}