<?php

namespace App\Filament\Portal\Resources\WorkLogResource\Pages;

use App\Filament\Portal\Resources\WorkLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWorkLogs extends ManageRecords
{
    protected static string $resource = WorkLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
