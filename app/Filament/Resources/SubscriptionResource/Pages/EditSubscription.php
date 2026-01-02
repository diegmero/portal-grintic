<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function ($record, $action) {
                    // Validar estado
                    if (in_array($record->status, [\App\Enums\SubscriptionStatus::ACTIVE, \App\Enums\SubscriptionStatus::PAUSED])) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Operación Bloqueada')
                            ->body('No se puede eliminar una suscripción Activa o Pausada. Cancélela primero.')
                            ->send();
                        $action->halt();
                    }
                    
                    // Validar historial
                    if ($record->periods()->whereNotNull('invoice_id')->exists()) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Operación Bloqueada')
                            ->body('No se puede eliminar una suscripción con historial de facturación.')
                            ->send();
                        $action->halt();
                    }
                }),
        ];
    }
}
