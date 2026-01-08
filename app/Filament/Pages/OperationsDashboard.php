<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OperationsDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'Centro de Operaciones';
    protected static ?string $title = 'Centro de Operaciones';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\PendingPaymentsWidget::class,
            \App\Filament\Widgets\ActiveSubscriptionsWidget::class,
            \App\Filament\Widgets\ProjectsStatusWidget::class,
            \App\Filament\Widgets\QuickWorkLogWidget::class,
        ];
    }
}
