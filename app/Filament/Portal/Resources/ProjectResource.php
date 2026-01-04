<?php

namespace App\Filament\Portal\Resources;

use App\Filament\Portal\Resources\ProjectResource\Pages;
use App\Filament\Portal\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Gestión';
    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Sección Unificada - Dashboard Card
                \Filament\Infolists\Components\Section::make()
                    ->schema([
                        // Fila 1: Header con Título y Estado
                        \Filament\Infolists\Components\Grid::make(2)
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('name')
                                    ->hiddenLabel()
                                    ->size('lg')
                                    ->weight('bold')
                                    ->icon('heroicon-o-folder'),
                                \Filament\Infolists\Components\TextEntry::make('status')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->alignEnd(),
                            ]),
                        
                        // Separador visual
                        \Filament\Infolists\Components\TextEntry::make('divider')
                            ->hiddenLabel()
                            ->state('')
                            ->extraAttributes(['class' => 'border-b border-gray-200 dark:border-gray-700 my-3']),
                        
                        // Fila 2: Stats Horizontales (4 métricas en línea)
                        \Filament\Infolists\Components\Grid::make(4)
                            ->schema([
                                \Filament\Infolists\Components\Group::make([
                                    \Filament\Infolists\Components\TextEntry::make('total_budget')
                                        ->label('Presupuesto')
                                        ->money('USD')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->color('success')
                                        ->icon('heroicon-o-banknotes'),
                                ]),
                                \Filament\Infolists\Components\Group::make([
                                    \Filament\Infolists\Components\TextEntry::make('deadline')
                                        ->label('Entrega')
                                        ->date('d M, Y')
                                        ->color(fn ($record) => $record->deadline && $record->deadline->isPast() ? 'danger' : 'primary')
                                        ->icon('heroicon-o-flag'),
                                ]),
                                \Filament\Infolists\Components\Group::make([
                                    \Filament\Infolists\Components\TextEntry::make('started_at')
                                        ->label('Inicio')
                                        ->date('d/m/Y')
                                        ->placeholder('Sin definir')
                                        ->icon('heroicon-o-play'),
                                ]),
                                \Filament\Infolists\Components\Group::make([
                                    \Filament\Infolists\Components\TextEntry::make('completed_at')
                                        ->label('Finalización')
                                        ->date('d/m/Y')
                                        ->placeholder('En curso')
                                        ->icon('heroicon-o-check-circle')
                                        ->color(fn ($state) => $state ? 'success' : 'gray'),
                                ]),
                            ]),
                        
                        // Separador visual
                        \Filament\Infolists\Components\TextEntry::make('divider2')
                            ->hiddenLabel()
                            ->state('')
                            ->extraAttributes(['class' => 'border-b border-gray-200 dark:border-gray-700 my-3']),
                        
                        // Fila 3: Descripción completa
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Descripción del Proyecto')
                            ->prose()
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Proyecto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('total_budget')
                    ->money('USD')
                    ->label('Presupuesto'),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->label('Fecha Límite'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TasksRelationManager::class,
            RelationManagers\DocumentationRelationManager::class,

            RelationManagers\InvoicesRelationManager::class,
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}
