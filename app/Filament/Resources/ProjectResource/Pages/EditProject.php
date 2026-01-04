<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function () {
                    $record = $this->record;
                    if ($record->invoiceItems()->exists() || $record->workLogs()->exists()) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Operación Bloqueada')
                            ->body('No se puede eliminar un proyecto que tiene historial financiero (Facturas, Items o Logs de Trabajo).')
                            ->persistent()
                            ->send();
                        
                        $this->halt();
                    }
                }),
        ];
    }
}
