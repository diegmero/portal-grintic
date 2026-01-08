<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class PendingPaymentsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()->where('status', 'pending')
            )
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Fecha')
                    ->date(),
                Tables\Columns\ImageColumn::make('attachment_path')
                    ->label('Comprobante')
                    ->disk('public')
                    ->visibility('public')
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Aprobar')
                    ->button()
                    ->color('success')
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'approved']);
                        
                        // Update Invoice status if applicable
                        if ($record->invoice) {
                            $paidAmount = $record->invoice->payments()->where('status', 'approved')->sum('amount');
                            if ($paidAmount >= $record->invoice->total) {
                                $record->invoice->update(['status' => 'paid']);
                            } else {
                                $record->invoice->update(['status' => 'partial']);
                            }
                        }

                        Notification::make()
                            ->title('Pago aprobado')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Rechazar')
                    ->button()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Pago rechazado')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}
