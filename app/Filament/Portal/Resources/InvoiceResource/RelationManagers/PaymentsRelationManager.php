<?php

namespace App\Filament\Portal\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método')
                    ->badge(),
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
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
