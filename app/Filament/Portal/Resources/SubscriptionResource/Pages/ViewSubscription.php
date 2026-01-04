<?php

namespace App\Filament\Portal\Resources\SubscriptionResource\Pages;

use App\Filament\Portal\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;
    
    protected function getHeaderActions(): array
    {
        return [];
    }
}
