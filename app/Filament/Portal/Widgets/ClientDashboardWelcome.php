<?php

namespace App\Filament\Portal\Widgets;

use Filament\Widgets\Widget;

class ClientDashboardWelcome extends Widget
{
    protected static string $view = 'filament.portal.widgets.client-dashboard-welcome';
    
    protected int | string | array $columnSpan = 'full';
    
    // Disable polling to save resources since it's static content
    protected static ?string $pollingInterval = null;
}
