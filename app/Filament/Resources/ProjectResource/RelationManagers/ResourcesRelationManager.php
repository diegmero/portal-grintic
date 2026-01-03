<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\LinkType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';
    
    protected static ?string $title = 'Recursos';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->label('Contenido de la Nota')
                    ->required()
                    ->rows(4)
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        $project = $this->getOwnerRecord();
        $linksCount = $project->links()->count();
        
        return $table
            ->heading("Notas ({$project->notes()->count()})")
            ->description($linksCount > 0 ? "Este proyecto tiene {$linksCount} enlaces guardados" : null)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label('Nota')
                    ->limit(40)
                    ->width('50%'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->default('Sistema')
                    ->toggleable()
                    ->width('20%'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y h:i A')
                    ->timezone('America/Bogota')
                    ->sortable()
                    ->width('30%'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva Nota')
                    ->icon('heroicon-o-document-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
                Tables\Actions\Action::make('addLink')
                    ->label('Nuevo Enlace')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->required()
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://...'),
                        
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options(LinkType::class)
                            ->default(LinkType::OTHER)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $this->getOwnerRecord()->links()->create($data);
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Enlace agregado')
                            ->send();
                    }),
                Tables\Actions\Action::make('viewLinks')
                    ->label('Ver Enlaces')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->visible(fn () => $this->getOwnerRecord()->links()->count() > 0)
                    ->modalHeading('Enlaces del Proyecto')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(function () {
                        $links = $this->getOwnerRecord()->links;
                        return view('filament.components.project-links-list', ['links' => $links]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Sin recursos')
            ->emptyStateDescription('Agrega notas para documentar el proyecto')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
