<?php

namespace App\Filament\Resources\WorkLogResource\Pages;

use App\Filament\Resources\WorkLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkLog extends EditRecord
{
    protected static string $resource = WorkLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn ($record) => $record->status === \App\Enums\WorkLogStatus::PENDING)
                ->before(function ($record, $action) {
                    if ($record->status !== \App\Enums\WorkLogStatus::PENDING) {
                         \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('No se puede eliminar')
                            ->body('Registro facturado/pagado.')
                            ->send();
                        $action->halt();
                    }
                }),
        ];
    }
}
