<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class ActiveSubscriptionsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Subscription::query()->whereIn('status', ['active', 'paused'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'paused' => 'warning',
                        'cancelled' => 'danger',
                    }),
            ])
            ->actions([
                Action::make('toggle_status')
                    ->label(fn (Subscription $record) => $record->status === 'active' ? 'Pausar' : 'Activar')
                    ->color(fn (Subscription $record) => $record->status === 'active' ? 'warning' : 'success')
                    ->button()
                    ->action(function (Subscription $record) {
                        $newStatus = $record->status === 'active' ? 'paused' : 'active';
                        $record->update(['status' => $newStatus]);
                        
                        Notification::make()
                            ->title('Estado actualizado')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
