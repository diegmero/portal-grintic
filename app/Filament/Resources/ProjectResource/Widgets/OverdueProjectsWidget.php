<?php

namespace App\Filament\Resources\ProjectResource\Widgets;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OverdueProjectsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'half';
    
    protected static ?string $heading = '⚠️ Proyectos con Alertas';

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
                Project::query()
                    ->whereNotNull('deadline')
                    ->where('status', '!=', ProjectStatus::DONE)
                    ->where(function ($query) {
                        // Proyectos vencidos (deadline pasado)
                        $query->whereDate('deadline', '<', now())
                            // O próximos a vencer (7 días)
                            ->orWhere(function ($q) {
                                $q->whereDate('deadline', '>=', now())
                                  ->whereDate('deadline', '<=', now()->addDays(7));
                            });
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Proyecto')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente')
                    ->limit(20),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Fecha Límite')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record->is_overdue ? 'danger' : 'warning'),
                
                Tables\Columns\TextColumn::make('days_until_deadline')
                    ->label('Días')
                    ->state(function ($record) {
                        $days = $record->days_until_deadline;
                        if ($days < 0) return abs($days) . ' días vencido';
                        if ($days === 0) return 'Vence hoy';
                        return $days . ' días restantes';
                    })
                    ->badge()
                    ->color(fn ($record) => $record->is_overdue ? 'danger' : 'warning'),
                
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progreso')
                    ->state(fn ($record) => $record->progress . '%')
                    ->badge()
                    ->color(fn ($record) => match(true) {
                        $record->progress >= 100 => 'success',
                        $record->progress >= 50 => 'info',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.projects.view', ['record' => $record])),
            ])
            ->paginated([5]);
    }
}
