<?php

namespace App\Filament\Portal\Resources\SubscriptionResource\RelationManagers;

use App\Enums\SubscriptionPeriodStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodsRelationManager extends RelationManager
{
    protected static string $relationship = 'periods';

    protected static ?string $title = 'Períodos de Facturación';

    protected static ?string $modelLabel = 'Período';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalles del Período')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('period_start')
                                    ->label('Inicio')
                                    ->required(),
                                Forms\Components\DatePicker::make('period_end')
                                    ->label('Fin')
                                    ->required(),
                                Forms\Components\TextInput::make('status')
                                    ->label('Estado')
                                    ->formatStateUsing(fn ($state) => \App\Enums\SubscriptionPeriodStatus::tryFrom($state)?->getLabel() ?? $state),
                            ]),
                        
                        Forms\Components\Textarea::make('work_description')
                            ->label('Descripción del Trabajo')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Sin descripción disponible.'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('period_start')
            ->defaultSort('period_start', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('period_label')
                    ->label('Período')
                    ->weight('bold')
                    ->sortable(['period_start']),
                
                Tables\Columns\TextColumn::make('period_start')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('period_end')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto Base')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Factura')
                    ->url(fn ($record) => $record->invoice ? route('filament.portal.resources.invoices.view', $record->invoice) : null)
                    ->placeholder('Sin facturar')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'gray'),

                Tables\Columns\TextColumn::make('extras')
                    ->label('Otros')
                    ->getStateUsing(function ($record) {
                        if ($record->invoice) {
                            $extras = $record->invoice->total - $record->amount;
                            return $extras > 0 ? $extras : 0;
                        }
                        return 0;
                    })
                    ->money('USD')
                    ->placeholder('$0.00')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),
                
                Tables\Columns\TextColumn::make('invoice.total')
                    ->label('Total')
                    ->money('USD')
                    ->placeholder('-')
                    ->weight('bold')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('attachments')
                    ->label('Adjuntos')
                    ->formatStateUsing(function ($state, $record) {
                        return !empty($record->attachments) ? '📎' : '';
                    })
                    ->badge(false)
                    ->color('info')
                    ->alignCenter()
                    ->tooltip(fn ($record) => !empty($record->attachments) ? count($record->attachments) . ' archivo(s)' : null)
                    ->action(
                        Tables\Actions\Action::make('previewAttachments')
                            ->visible(fn ($record) => !empty($record->attachments))
                            ->modalHeading('Archivos Adjuntos')
                            ->modalWidth('5xl')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(fn ($action) => $action->label('Cerrar'))
                            ->modalContent(fn ($record) => view('filament.components.subscription-period-attachments-preview', [
                                'attachments' => $record->attachments,
                            ]))
                    ),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detalles del Período')
                    ->modalWidth('lg'),
            ])
            ->bulkActions([]);
    }
}
