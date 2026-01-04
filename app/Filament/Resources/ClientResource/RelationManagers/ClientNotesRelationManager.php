<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClientNotesRelationManager extends RelationManager
{
    protected static string $relationship = 'clientNotes';
    
    protected static ?string $title = 'Notas Internas';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->label('Nota')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label('Nota')
                    ->limit(100)
                    ->width('60%'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->default('Sistema')
                    ->width('20%'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y h:i A')
                    ->timezone('America/Bogota')
                    ->sortable()
                    ->width('20%'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva Nota')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Sin notas')
            ->emptyStateDescription('Agrega notas internas sobre este cliente')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
