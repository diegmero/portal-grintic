<?php

namespace App\Filament\Resources\ProjectResource\Widgets;

use App\Enums\TaskStatus;
use App\Models\ProjectTask;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OverdueTasksWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'half';
    
    protected static ?string $heading = '📋 Tareas con Alertas';

    protected static bool $isLazy = false;

    protected function getTableContentGrid(): ?array
    {
        return null;
    }

    public function getTableMaxHeight(): ?string
    {
        return '300px';
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->query(
                ProjectTask::query()
                    ->whereNotNull('due_date')
                    ->where('status', '!=', TaskStatus::COMPLETED)
                    ->where(function ($query) {
                        // Tareas vencidas
                        $query->whereDate('due_date', '<', now())
                            // O próximas a vencer (7 días)
                            ->orWhere(function ($q) {
                                $q->whereDate('due_date', '>=', now())
                                  ->whereDate('due_date', '<=', now()->addDays(7));
                            });
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tarea')
                    ->limit(25),
                
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyecto')
                    ->limit(15),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record->due_date->isPast() ? 'danger' : 'warning'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.projects.view', ['record' => $record->project_id])),
            ])
            ->paginated([5]);
    }
}
