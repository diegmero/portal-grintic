<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\TaskStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';
    
    protected static ?string $title = 'Tareas';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
                
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpan('full'),
                
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options(TaskStatus::class)
                    ->default(TaskStatus::PENDING)
                    ->required(),
                
                Forms\Components\DatePicker::make('due_date')
                    ->label('Fecha Límite'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tarea')
                    ->searchable()
                    ->limit(40)
                    ->width('50%'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->width('25%'),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record->due_date?->isPast() && $record->status !== TaskStatus::COMPLETED ? 'danger' : null)
                    ->width('25%'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva Tarea')
                    ->after(fn () => $this->dispatch('$refresh')),
            ])
            ->actions([
                Tables\Actions\Action::make('complete')
                    ->label('Completar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== TaskStatus::COMPLETED)
                    ->action(function ($record) {
                        $record->update(['status' => TaskStatus::COMPLETED]);
                        $this->dispatch('$refresh');
                    }),
                Tables\Actions\Action::make('in_progress')
                    ->label('En Progreso')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === TaskStatus::PENDING)
                    ->action(function ($record) {
                        $record->update(['status' => TaskStatus::IN_PROGRESS]);
                        $this->dispatch('$refresh');
                    }),
                Tables\Actions\EditAction::make()
                    ->after(fn () => $this->dispatch('$refresh')),
                Tables\Actions\DeleteAction::make()
                    ->after(fn () => $this->dispatch('$refresh')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(fn () => $this->dispatch('$refresh')),
            ]);
    }
}
