<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;
    
    protected static string $view = 'filament.resources.client-resource.pages.view-client';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Información del Cliente')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Components\TextEntry::make('company_name')
                            ->label('Empresa')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Components\TextEntry::make('country_name')
                            ->label('País')
                            ->placeholder('No definido'),
                        Components\TextEntry::make('formatted_tax_id')
                            ->label('ID Fiscal')
                            ->placeholder('No definido'),
                        Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge(),
                        Components\TextEntry::make('internal_notes')
                            ->label('Notas')
                            ->placeholder('Sin notas')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
                
                Components\Section::make('Estadísticas')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('contacts_count')
                                    ->label('Contactos')
                                    ->state(fn ($record) => $record->contacts()->count())
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->color('info'),
                                Components\TextEntry::make('subscriptions_count')
                                    ->label('Suscripciones')
                                    ->state(fn ($record) => $record->subscriptions()->count())
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->color('warning'),
                                Components\TextEntry::make('projects_count')
                                    ->label('Proyectos')
                                    ->state(fn ($record) => $record->projects()->count())
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->color('success'),
                            ]),
                    ]),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\ClientResource\RelationManagers\ContactsRelationManager::class,
            \App\Filament\Resources\ClientResource\RelationManagers\SubscriptionsRelationManager::class,
            \App\Filament\Resources\ClientResource\RelationManagers\ProjectsRelationManager::class,
        ];
    }
}
