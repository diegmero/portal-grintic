<?php

namespace App\Filament\Resources;

use App\Enums\ClientStatus;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationLabel = 'Clientes';
    
    protected static ?string $modelLabel = 'Cliente';
    
    protected static ?string $pluralModelLabel = 'Clientes';
    
    protected static ?string $navigationGroup = 'CRM';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Cliente')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Nombre de la Empresa')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),
                        
                        Forms\Components\TextInput::make('tax_id')
                            ->label('NIT/RUT/VAT')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(ClientStatus::class)
                            ->default(ClientStatus::ACTIVE)
                            ->required(),
                        
                        Forms\Components\Textarea::make('internal_notes')
                            ->label('Notas Internas')
                            ->rows(3)
                            ->columnSpan('full'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tax_id')
                    ->label('NIT/RUT')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subscriptions_count')
                    ->label('Suscripciones')
                    ->counts('subscriptions')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(ClientStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información del Cliente')
                    ->schema([
                        Infolists\Components\TextEntry::make('company_name')
                            ->label('Nombre de la Empresa'),
                        Infolists\Components\TextEntry::make('tax_id')
                            ->label('NIT/RUT/VAT'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge(),
                        Infolists\Components\TextEntry::make('internal_notes')
                            ->label('Notas Internas')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Estadísticas')
                    ->schema([
                        Infolists\Components\TextEntry::make('contacts_count')
                            ->label('Contactos')
                            ->state(fn ($record) => $record->contacts()->count()),
                        Infolists\Components\TextEntry::make('assets_count')
                            ->label('Activos')
                            ->state(fn ($record) => $record->assets()->count()),
                        Infolists\Components\TextEntry::make('subscriptions_count')
                            ->label('Suscripciones')
                            ->state(fn ($record) => $record->subscriptions()->count()),
                        Infolists\Components\TextEntry::make('projects_count')
                            ->label('Proyectos')
                            ->state(fn ($record) => $record->projects()->count()),
                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContactsRelationManager::class,
            RelationManagers\AssetsRelationManager::class,
            RelationManagers\SubscriptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }
}