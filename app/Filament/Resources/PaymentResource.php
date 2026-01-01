<?php

namespace App\Filament\Resources;

use App\Enums\PaymentMethod;
use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Pagos';
    
    protected static ?string $modelLabel = 'Pago';
    
    protected static ?string $pluralModelLabel = 'Pagos';
    
    protected static ?string $navigationGroup = 'Facturación';
    
    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('invoice_id')
                            ->label('Factura')
                            ->relationship('invoice', 'invoice_number')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan('full'),
                        
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0.01),
                        
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
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->columnSpan('full'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpan('full'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Factura')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('invoice.client.company_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
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
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('transaction_reference')
                    ->label('Referencia')
                    ->limit(20)
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Método')
                    ->options(PaymentMethod::class),
                
                Tables\Filters\Filter::make('payment_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('payment_date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('payment_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('payment_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}