<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\PaymentResource\Pages;
use App\Filament\Portal\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Facturación';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('invoice_id')
                    ->relationship('invoice', 'id')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('payment_date')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('transaction_reference')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('attachment_path')
                    ->maxLength(500)
                    ->default(null),
                Forms\Components\TextInput::make('attachment_original_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Factura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->label('Monto'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->label('Fecha'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método')
                    ->badge(),
                Tables\Columns\TextColumn::make('transaction_reference')
                    ->label('Ref. Transacción')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attachment_path')
                    ->label('Comprobante')
                    ->formatStateUsing(fn ($state) => $state ? '📎 Ver' : '-')
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
                            ->modalHeading('Comprobante de Pago')
                    ),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('invoice', function ($query) {
            $query->where('client_id', auth()->user()->client_id);
        });
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePayments::route('/'),
        ];
    }
}
