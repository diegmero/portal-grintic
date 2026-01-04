<?php

namespace App\Filament\Resources;

use App\Enums\WorkLogStatus;
use App\Filament\Resources\WorkLogResource\Pages;
use App\Models\WorkLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class WorkLogResource extends Resource
{
    protected static ?string $model = WorkLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationLabel = 'Soporte - Horas';
    
    protected static ?string $modelLabel = 'Soporte - Horas';
    
    protected static ?string $pluralModelLabel = 'Soporte - Horas';
    
    protected static ?string $navigationGroup = 'Servicios';
    
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detalle Económico')
                            ->schema([
                                Forms\Components\TextInput::make('hours')
                                    ->label('Horas')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.25)
                                    ->step(0.25)
                                    ->suffix('hrs')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $hours = (float) $state;
                                        $rate = (float) $get('hourly_rate');
                                        $set('calculated_total', $hours * $rate);
                                    })
                                    ->disabled(fn ($record) => $record && $record->status !== WorkLogStatus::PENDING),
                                
                                Forms\Components\TextInput::make('hourly_rate')
                                    ->label('Tarifa/Hora')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $hours = (float) $get('hours');
                                        $rate = (float) $state;
                                        $set('calculated_total', $hours * $rate);
                                    })
                                    ->disabled(fn ($record) => $record && $record->status !== WorkLogStatus::PENDING),
                                
                                Forms\Components\Placeholder::make('calculated_total')
                                    ->label('Total')
                                    ->content(function (Forms\Get $get) {
                                        $hours = (float) ($get('hours') ?? 0);
                                        $rate = (float) ($get('hourly_rate') ?? 0);
                                        $total = $hours * $rate;
                                        return '$' . number_format($total, 2);
                                    }),
                            ])
                            ->columns(3),

                        Forms\Components\Section::make('Descripción')
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->hiddenLabel()
                                    ->placeholder('Describe el trabajo realizado...')
                                    ->required()
                                    ->rows(5),
                            ]),
                    ])
                    ->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Contexto del Cliente')
                            ->schema([
                                Forms\Components\Select::make('client_id')
                                    ->label('Cliente')
                                    ->relationship('client', 'company_name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn ($record) => $record && $record->status !== WorkLogStatus::PENDING),
                                
                                Forms\Components\Select::make('service_id')
                                    ->label('Servicio')
                                    ->relationship('service', 'name', function ($query) {
                                        $query->where('type', 'hourly');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $service = \App\Models\Service::find($state);
                                            $set('hourly_rate', $service->base_price);
                                        }
                                    })
                                    ->disabled(fn ($record) => $record && $record->status !== WorkLogStatus::PENDING),
                                
                                Forms\Components\DatePicker::make('worked_at')
                                    ->label('Fecha')
                                    ->default(now())
                                    ->required()
                                    ->maxDate(now())
                                    ->disabled(fn ($record) => $record && $record->status !== WorkLogStatus::PENDING),

                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options(WorkLogStatus::class)
                                    ->default(WorkLogStatus::PENDING)
                                    ->required()
                                    ->disabled(fn ($record) => $record && $record->status !== WorkLogStatus::PENDING),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('worked_at')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas')
                    ->suffix(' hrs')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('hourly_rate')
                    ->label('Tarifa')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderByRaw('hours * hourly_rate ' . $direction);
                    }),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->description)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(WorkLogStatus::class),
                
                Tables\Filters\Filter::make('worked_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('worked_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('worked_at', '<=', $date));
                    }),
                
                Tables\Filters\Filter::make('pending')
                    ->label('Solo Pendientes')
                    ->query(fn ($query) => $query->pending())
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generate_invoice')
                    ->label('Facturar')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === WorkLogStatus::PENDING)
                    ->action(function ($record) {
                        return \DB::transaction(function () use ($record) {
                            // Crear factura (invoice_number se genera automáticamente)
                            $invoice = \App\Models\Invoice::create([
                                'client_id' => $record->client_id,
                                'issue_date' => now(),
                                'due_date' => now()->addDays(30),
                                'subtotal' => 0,
                                'tax_percentage' => 0,
                                'tax_amount' => 0,
                                'total' => 0,
                                'status' => \App\Enums\InvoiceStatus::INVOICED,
                            ]);
                            
                            // Agregar item
                            $invoice->invoiceItems()->create([
                                'description' => $record->description . " ({$record->hours} hrs @ $" . number_format($record->hourly_rate, 2) . ")",
                                'quantity' => 1,
                                'unit_price' => $record->total,
                                'subtotal' => $record->total,
                                'itemable_type' => \App\Models\WorkLog::class,
                                'itemable_id' => $record->id,
                            ]);
                            
                            $record->status = WorkLogStatus::INVOICED;
                            $record->save();
                            
                            // Calcular totales
                            $invoice->calculateTotals();
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Factura generada')
                                ->body("Factura {$invoice->invoice_number} creada")
                                ->send();
                            
                            return redirect()->route('filament.admin.resources.invoices.view', ['record' => $invoice]);
                        });
                    })
                    ->requiresConfirmation(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records, Tables\Actions\DeleteBulkAction $action) {
                            if ($records->contains(fn ($record) => $record->status !== \App\Enums\WorkLogStatus::PENDING)) {
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Operación Bloqueada')
                                    ->body('No puedes eliminar registros que ya han sido facturados o pagados.')
                                    ->send();
                                
                                $action->halt();
                            }
                        }),


                ]),
            ])
            ->defaultSort('worked_at', 'desc');
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make('Detalle Económico')
                            ->schema([
                                Infolists\Components\TextEntry::make('hours')
                                    ->label('Horas')
                                    ->suffix(' hrs'),
                                Infolists\Components\TextEntry::make('hourly_rate')
                                    ->label('Tarifa/Hora')
                                    ->money('USD'),
                                Infolists\Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->money('USD')
                                    ->weight('bold')
                                    ->size('lg'),
                            ])
                            ->columns(3),

                        Infolists\Components\Section::make('Descripción del Trabajo')
                            ->schema([
                                Infolists\Components\TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->html()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(2),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make('Contexto del Cliente')
                            ->schema([
                                Infolists\Components\TextEntry::make('client.company_name')
                                    ->label('Cliente'),
                                Infolists\Components\TextEntry::make('service.name')
                                    ->label('Servicio'),
                                Infolists\Components\TextEntry::make('worked_at')
                                    ->label('Fecha')
                                    ->date('d/m/Y'),
                                Infolists\Components\TextEntry::make('status')
                                    ->label('Estado')
                                    ->badge(),
                            ]),

                        Infolists\Components\Section::make('Facturación')
                            ->schema([
                                Infolists\Components\TextEntry::make('invoiceItem.invoice.invoice_number')
                                    ->label('Nº Factura')
                                    ->icon('heroicon-o-document-text')
                                    ->color('primary')
                                    ->url(fn ($record) => $record->invoiceItem?->invoice ? route('filament.admin.resources.invoices.view', ['record' => $record->invoiceItem->invoice]) : null),
                                
                                Infolists\Components\TextEntry::make('invoiceItem.invoice.status')
                                    ->label('Estado')
                                    ->badge(),
                                
                                Infolists\Components\TextEntry::make('invoiceItem.invoice.total')
                                    ->label('Monto')
                                    ->money('USD'),
                            ])
                            ->visible(fn ($record) => $record->invoiceItem !== null),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkLogs::route('/'),
            'create' => Pages\CreateWorkLog::route('/create'),
            'edit' => Pages\EditWorkLog::route('/{record}/edit'),
            'view' => Pages\ViewWorkLog::route('/{record}'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::pending()->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}