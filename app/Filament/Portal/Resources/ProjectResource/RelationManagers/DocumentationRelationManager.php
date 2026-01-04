<?php

namespace App\Filament\Portal\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentationRelationManager extends RelationManager
{
    protected static string $relationship = 'documentation';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attachments')
                    ->label('Adjuntos')
                    ->formatStateUsing(function ($state, $record) {
                        // Cast manually if needed for safety, though model should handle it
                        $attachments = $record->attachments;
                        if (is_string($attachments)) {
                             $attachments = json_decode($attachments, true);
                        }
                        
                        if (is_array($attachments) && count($attachments) > 0) {
                            return '📎';
                        }
                        return '-';
                    })
                    ->color(fn ($state, $record) => !empty($record->attachments) ? 'info' : 'gray')
                    ->tooltip(function ($state, $record) {
                        $attachments = $record->attachments;
                         if (is_string($attachments)) {
                             $attachments = json_decode($attachments, true);
                        }
                        return (is_array($attachments) && count($attachments) > 0) ? count($attachments) . ' archivo(s)' : 'Sin adjuntos';
                    })
                    ->alignCenter()
                    ->action(
                        Tables\Actions\Action::make('previewAttachments')
                            ->visible(function ($record) {
                                $attachments = $record->attachments;
                                if (is_string($attachments)) {
                                    $attachments = json_decode($attachments, true);
                                }
                                return $attachments && is_array($attachments) && count($attachments) > 0;
                            })
                            ->modalContent(function ($record) {
                                $attachments = $record->attachments;
                                if (is_string($attachments)) {
                                    $attachments = json_decode($attachments, true);
                                }
                                return view('filament.components.documentation-attachments-preview', [
                                    'attachments' => $attachments,
                                ]);
                            })
                            ->modalSubmitAction(false)
                            ->modalCancelAction(fn ($action) => $action->label('Cerrar'))
                            ->modalWidth('5xl')
                    ),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->default('Sistema')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->date()
                    ->sortable(),
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
