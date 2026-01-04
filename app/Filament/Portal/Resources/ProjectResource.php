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

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Project Details')
                    ->tabs([
                        Tabs\Tab::make('Resumen')
                            ->schema([
                                Section::make('Información General')
                                    ->schema([
                                        TextEntry::make('name')->label('Nombre'),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->label('Estado'),
                                        TextEntry::make('total_budget')
                                            ->money('USD')
                                            ->label('Presupuesto'),
                                        TextEntry::make('deadline')
                                            ->date()
                                            ->label('Fecha Límite'),
                                        TextEntry::make('started_at')
                                            ->date()
                                            ->label('Inicio'),
                                        TextEntry::make('completed_at')
                                            ->date()
                                            ->label('Fin'),
                                    ])->columns(2),
                                Section::make('Descripción')
                                    ->schema([
                                        TextEntry::make('description')->columnSpanFull(),
                                        TextEntry::make('technologies')
                                            ->badge()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
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
