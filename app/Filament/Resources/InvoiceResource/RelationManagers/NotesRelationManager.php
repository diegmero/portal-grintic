<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    
    protected static ?string $title = 'Notas';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('Contenido')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice_number')
            ->columns([
                Tables\Columns\TextColumn::make('notes')
                    ->label('Contenido de Notas')
                    ->markdown()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // No create action needed as it's a 1-to-1 self relation
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar Notas'),
            ])
            ->bulkActions([
                //
            ])
            ->paginated(false);
    }
}
