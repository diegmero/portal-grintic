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
    
    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 21;

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
                            ->disabled()
                            ->columnSpan('full'),
                        
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0.01)
                            ->disabled(),
                        
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Fecha de Pago')
                            ->default(now())
                            ->required()
                            ->maxDate(now())
                            ->disabled(),
                            
                        Forms\Components\Placeholder::make('client_name')
                            ->label('Cliente')
                            ->content(fn ($record) => $record?->invoice?->client?->company_name ?? '-'),
                            
                        Forms\Components\Placeholder::make('context')
                            ->label('Concepto / Servicio')
                            ->content(function ($record) {
                                if (!$record || !$record->invoice) return '-';
                                $items = $record->invoice->invoiceItems;
                                if ($items->isEmpty()) return '-';
                                
                                return $items->pluck('itemable_type')->unique()->map(function ($type) {
                                    return match($type) {
                                        'App\Models\Project' => 'Proyecto',
                                        'App\Models\SubscriptionPeriod' => 'Suscripción',
                                        'App\Models\WorkLog' => 'Soporte - Horas',
                                        default => 'Otro',
                                    };
                                })->join(', ');
                            }),
                        
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
                            ->label('Comprobante de Pago')
                            ->disabled()
                            ->columnSpan('full'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpan('full')
                            ->helperText('Este es el único campo editable'),
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