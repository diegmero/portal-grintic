<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\DocumentationCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentationRelationManager extends RelationManager
{
    protected static string $relationship = 'documentation';
    
    protected static ?string $title = 'Documentación';

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
                
                Forms\Components\Select::make('category')
                    ->label('Categoría')
                    ->options(DocumentationCategory::class)
                    ->required()
                    ->default(DocumentationCategory::OTHER)
                    ->columnSpan('full'),
                
                Forms\Components\RichEditor::make('content')
                    ->label('Contenido')
                    ->toolbarButtons([
                        'bold', 'italic', 'underline',
                        'h2', 'h3',
                        'bulletList', 'orderedList',
                        'link', 'blockquote', 'codeBlock',
                    ])
                    ->columnSpan('full'),
                
                Forms\Components\FileUpload::make('attachments')
                    ->label('Archivos Adjuntos (Opcional)')
                    ->multiple()
                    ->maxFiles(3)
                    ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                    ->directory('project-documentation')
                    ->visibility('private')
                    ->downloadable()
                    ->previewable()
                    ->helperText('Máximo 3 archivos. Formatos: PDF, PNG, JPG, JPEG. Tamaño máximo recomendado: 10MB por archivo')
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Documentación del Proyecto')
            ->description('Gestiona la documentación técnica y guías del proyecto')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('attachments')
                    ->label('Adjuntos')
                    ->formatStateUsing(function ($state, $record) {
                        if (is_array($record->attachments) && count($record->attachments) > 0) {
                            return '📎';
                        }
                        return '-';
                    })
                    ->color(fn ($state, $record) => is_array($record->attachments) && count($record->attachments) > 0 ? 'info' : 'gray')
                    ->tooltip(fn ($state, $record) => is_array($record->attachments) && count($record->attachments) > 0 ? count($record->attachments) . ' archivo(s)' : 'Sin adjuntos')
                    ->alignCenter()
                    ->action(
                        Tables\Actions\Action::make('previewAttachments')
                            ->visible(fn ($record) => $record->attachments && is_array($record->attachments) && count($record->attachments) > 0)
                            ->modalContent(fn ($record) => view('filament.components.documentation-attachments-preview', [
                                'attachments' => $record->attachments,
                            ]))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(fn ($action) => $action->label('Cerrar'))
                            ->modalWidth('5xl')
                    ),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->default('Sistema')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime('d/m/Y h:i A')
                    ->timezone('America/Bogota')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(DocumentationCategory::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva Documentación')
                    ->icon('heroicon-o-document-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => $record->title)
                    ->modalContent(fn ($record) => view('filament.components.documentation-view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Sin documentación')
            ->emptyStateDescription('Haz clic en "Nueva Documentación" arriba para comenzar')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
