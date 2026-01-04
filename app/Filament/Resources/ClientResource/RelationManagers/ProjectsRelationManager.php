<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Enums\ProjectStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';
    
    protected static ?string $title = 'Proyectos';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Proyecto')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->toolbarButtons([
                        'bold', 'italic', 'bulletList', 'orderedList', 'link',
                    ])
                    ->columnSpanFull(),
                
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options(ProjectStatus::class)
                    ->default(ProjectStatus::PLANNING)
                    ->required(),
                
                Forms\Components\TextInput::make('total_budget')
                    ->label('Presupuesto Total')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                
                Forms\Components\DatePicker::make('started_at')
                    ->label('Fecha de Inicio'),
                
                Forms\Components\DatePicker::make('deadline')
                    ->label('Fecha Límite'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->limit(40)
                    ->width('40%'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->width('30%'),
                Tables\Columns\TextColumn::make('total_budget')
                    ->label('Presupuesto')
                    ->money('USD')
                    ->width('30%'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Proyecto'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => \App\Filament\Resources\ProjectResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
