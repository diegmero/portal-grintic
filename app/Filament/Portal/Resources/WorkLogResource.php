<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\WorkLogResource\Pages;
use App\Filament\Portal\Resources\WorkLogResource\RelationManagers;
use App\Models\WorkLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class WorkLogResource extends Resource
{
    protected static ?string $model = WorkLog::class;

    protected static ?string $modelLabel = 'Horas Soporte';
    protected static ?string $pluralModelLabel = 'Horas Soporte';
    protected static ?string $navigationGroup = 'Soporte';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Servicio'),
                Forms\Components\TextInput::make('hours')
                    ->label('Horas')
                    ->numeric(),
                Forms\Components\TextInput::make('hourly_rate')
                    ->label('Tarifa/Hora')
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('worked_at')
                    ->label('Fecha'),
                Forms\Components\TextInput::make('status')
                    ->label('Estado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('worked_at')
                    ->date()
                    ->label('Fecha')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->numeric()
                    ->label('Horas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoiceItem.invoice.invoice_number')
                    ->label('Factura')
                    ->placeholder('-')
                    ->color('info')
                    ->weight('bold')
                    ->url(fn ($record) => $record->invoiceItem?->invoice ? \App\Filament\Portal\Resources\InvoiceResource::getUrl('view', ['record' => $record->invoiceItem->invoice]) : null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('USD')
                    ->label('Total'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Estado'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWorkLogs::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('client_id', auth()->user()->client_id);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
