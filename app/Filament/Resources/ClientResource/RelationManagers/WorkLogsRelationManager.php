<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Enums\WorkLogStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WorkLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'workLogs';
    
    protected static ?string $title = 'Soporte - Horas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Describe el trabajo realizado...')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Soporte - Horas')
            ->description('Registros de horas de soporte para este cliente')
            ->defaultSort('worked_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->sortable(),
                
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
                    ->sortable(),
                
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->emptyStateHeading('Sin registros de soporte')
            ->emptyStateDescription('Los registros se crean desde el módulo "Registro de Horas"')
            ->emptyStateIcon('heroicon-o-clock');
    }
}
