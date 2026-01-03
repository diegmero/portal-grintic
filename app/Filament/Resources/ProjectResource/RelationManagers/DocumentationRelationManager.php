<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\Infrastructure;
use App\Enums\Technology;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentationRelationManager extends RelationManager
{
    protected static string $relationship = 'notes'; // Required placeholder
    
    protected static ?string $title = 'Documentación';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        $project = $this->getOwnerRecord();
        
        return $table
            ->paginated(false)
            ->columns([])
            ->heading('')
            ->headerActions([
                Tables\Actions\Action::make('editDocumentation')
                    ->label('Editar Documentación')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->form([
                        Forms\Components\Section::make('Descripción del Proyecto')
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->label('')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline',
                                        'h2', 'h3',
                                        'bulletList', 'orderedList',
                                        'link', 'blockquote', 'codeBlock',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),
                        
                        Forms\Components\Section::make('Stack Tecnológico')
                            ->description('Selecciona las tecnologías utilizadas en este proyecto')
                            ->schema([
                                Forms\Components\CheckboxList::make('technologies')
                                    ->label('')
                                    ->options(Technology::class)
                                    ->columns(4)
                                    ->gridDirection('row')
                                    ->searchable(),
                            ])
                            ->collapsible(),
                        
                        Forms\Components\Section::make('Infraestructura')
                            ->description('Selecciona los servicios de infraestructura utilizados')
                            ->schema([
                                Forms\Components\CheckboxList::make('infrastructure')
                                    ->label('')
                                    ->options(Infrastructure::class)
                                    ->columns(4)
                                    ->gridDirection('row')
                                    ->searchable(),
                            ])
                            ->collapsible(),
                        
                        Forms\Components\Section::make('Notas Técnicas')
                            ->description('Configuraciones especiales, instrucciones de deploy, etc.')
                            ->schema([
                                Forms\Components\RichEditor::make('technical_notes')
                                    ->label('')
                                    ->toolbarButtons([
                                        'bold', 'italic',
                                        'bulletList', 'orderedList',
                                        'link', 'codeBlock',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),
                    ])
                    ->fillForm(fn () => [
                        'description' => $project->description,
                        'technologies' => $project->technologies ?? [],
                        'infrastructure' => $project->infrastructure ?? [],
                        'technical_notes' => $project->technical_notes,
                    ])
                    ->action(function (array $data) use ($project) {
                        $project->update([
                            'description' => $data['description'],
                            'technologies' => $data['technologies'],
                            'infrastructure' => $data['infrastructure'],
                            'technical_notes' => $data['technical_notes'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Documentación actualizada')
                            ->send();
                    })
                    ->modalWidth('5xl')
                    ->modalHeading('Editar Documentación del Proyecto'),
            ])
            ->emptyStateHeading('')
            ->emptyStateDescription('');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getOwnerRecord())
            ->schema([
                Infolists\Components\Section::make('Descripción')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->hiddenLabel()
                            ->html()
                            ->placeholder('Sin descripción definida'),
                    ])
                    ->collapsible(),
                
                Infolists\Components\Grid::make(2)
                    ->schema([
                        Infolists\Components\Section::make('Stack Tecnológico')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Infolists\Components\TextEntry::make('technologies')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->formatStateUsing(fn ($state) => $state ? Technology::tryFrom($state)?->getLabel() ?? $state : null)
                                    ->color(fn ($state) => $state ? Technology::tryFrom($state)?->getColor() ?? 'gray' : 'gray')
                                    ->placeholder('Sin tecnologías definidas'),
                            ])
                            ->columnSpan(1),
                        
                        Infolists\Components\Section::make('Infraestructura')
                            ->icon('heroicon-o-server')
                            ->schema([
                                Infolists\Components\TextEntry::make('infrastructure')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->formatStateUsing(fn ($state) => $state ? Infrastructure::tryFrom($state)?->getLabel() ?? $state : null)
                                    ->color(fn ($state) => $state ? Infrastructure::tryFrom($state)?->getColor() ?? 'gray' : 'gray')
                                    ->placeholder('Sin infraestructura definida'),
                            ])
                            ->columnSpan(1),
                    ]),
                
                Infolists\Components\Section::make('Notas Técnicas')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->schema([
                        Infolists\Components\TextEntry::make('technical_notes')
                            ->hiddenLabel()
                            ->html()
                            ->placeholder('Sin notas técnicas'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('filament.resources.project-resource.relation-managers.documentation-native', [
            'project' => $this->getOwnerRecord(),
            'infolist' => $this->infolist(new Infolist()),
        ]);
    }
}
