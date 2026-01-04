<?php

namespace App\Filament\Resources;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Facturas';
    
    protected static ?string $modelLabel = 'Factura';
    
    protected static ?string $pluralModelLabel = 'Facturas';
    
    protected static ?string $navigationGroup = 'Facturación';
    
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->label('Número de Factura')
                            ->disabled()
                            ->dehydrated(false)
                            ->hint('Auto-generado'),
                        
                        Forms\Components\Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'company_name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn ($record) => $record !== null),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(InvoiceStatus::class)
                            ->default(InvoiceStatus::DRAFT)
                            ->required()
                            ->disabled(fn ($record) => $record !== null),
                            
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Fecha de Emisión')
                            ->default(now())
                            ->required()
                            ->disabled(fn ($record) => $record !== null)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $dueDate = \Carbon\Carbon::parse($state)->addDays(30);
                                    $set('due_date', $dueDate->format('Y-m-d'));
                                }
                            }),
                        
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Fecha de Vencimiento')
                            ->required()
                            ->minDate(fn (Forms\Get $get) => $get('issue_date')),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Detalles Económicos')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\TextInput::make('tax_percentage')
                            ->label('Porcentaje Impuesto')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),
                        
                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Monto Impuesto')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(4),
                
                Forms\Components\Section::make('Notas Adicionales')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Tipo de Servicio')
                    ->state(function ($record) {
                        $items = $record->invoiceItems;
                        if ($items->isEmpty()) return 'Sin items';
                        
                        $types = $items->pluck('itemable_type')->unique()->map(function ($type) {
                            return match($type) {
                                'App\Models\Project' => 'Proyecto',
                                'App\Models\SubscriptionPeriod' => 'Suscripción',
                                'App\Models\WorkLog' => 'Trabajo',
                                default => 'Otro',
                            };
                        });
                        
                        return $types->join(', ');
                    })
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() && $record->status !== InvoiceStatus::PAID ? 'danger' : null),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('payments_sum_amount')
                    ->label('Pagado')
                    ->sum('payments', 'amount')
                    ->money('USD')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(collect(InvoiceStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])->toArray()),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('Vencidas')
                    ->query(fn ($query) => $query->overdue())
                    ->toggle(),
                
                Tables\Filters\Filter::make('unpaid')
                    ->label('No Pagadas')
                    ->query(fn ($query) => $query->unpaid())
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status !== InvoiceStatus::PAID),
                Tables\Actions\Action::make('send')
                    ->label('Enviar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === InvoiceStatus::DRAFT)
                    ->action(function ($record) {
                        $record->status = InvoiceStatus::INVOICED;
                        $record->save();
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Factura enviada')
                            ->send();
                    })
                    ->requiresConfirmation(),
                
                Tables\Actions\Action::make('registerPayment')
                    ->label('Registrar Pago')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== \App\Enums\InvoiceStatus::PAID)
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Fecha de Pago')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options(\App\Enums\PaymentMethod::class)
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->prefix('$')
                            ->default(fn ($record) => $record->total)
                            ->readOnly(),
                        Forms\Components\TextInput::make('transaction_reference')
                            ->label('Referencia')
                            ->required(),
                        Forms\Components\FileUpload::make('attachment_path')
                            ->label('Comprobante')
                            ->disk('local')
                            ->directory('payments')
                            ->visibility('private')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(3072)
                            ->required()
                            ->columnSpan('full')
                            ->hint('PDF o imagen, máximo 3MB'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2)
                            ->columnSpan('full'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->payments()->create([
                            'payment_date' => $data['payment_date'],
                            'payment_method' => $data['payment_method'],
                            'amount' => $data['amount'],
                            'transaction_reference' => $data['transaction_reference'],
                            'attachment_path' => $data['attachment_path'],
                            'notes' => $data['notes'],
                        ]);
                        
                        // Usar markAsPaid para sincronizar estados de períodos
                        $record->markAsPaid();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pago registrado correctamente')
                            ->body('La factura y períodos vinculados han sido marcados como pagados.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('issue_date', 'desc');
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información General')
                    ->schema([
                        Infolists\Components\TextEntry::make('invoice_number')
                            ->label('Número de Factura')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('client.company_name')
                            ->label('Cliente'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge(),
                        Infolists\Components\TextEntry::make('issue_date')
                            ->label('Fecha de Emisión')
                            ->date('d/m/Y'),
                        Infolists\Components\TextEntry::make('due_date')
                            ->label('Fecha de Vencimiento')
                            ->date('d/m/Y'),
                    ])
                    ->columns(1),
                
                Infolists\Components\Section::make('Detalles Económicos')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('tax_amount')
                            ->label('Impuestos')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('total')
                            ->label('Total')
                            ->money('USD')
                            ->weight('bold')
                            ->size('lg'),
                        Infolists\Components\TextEntry::make('payments_sum_amount')
                            ->label('Pagado')
                            ->state(fn ($record) => $record->payments()->sum('amount'))
                            ->money('USD')
                            ->color('success'),
                    ])
                    ->columns(1),
                
                Infolists\Components\Section::make('Notas')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Notas')
                            ->visible(fn ($record) => $record->notes),
                    ])
                    ->columns(1),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InvoiceItemsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::unpaid()->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
