<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Ingresos este mes
        $revenueThisMonth = \App\Models\Invoice::where('status', \App\Enums\InvoiceStatus::PAID)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $revenueLastMonth = \App\Models\Invoice::where('status', \App\Enums\InvoiceStatus::PAID)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');
            
        $revenueTrend = $revenueThisMonth >= $revenueLastMonth ? 'increase' : 'decrease';
        $revenueIcon = $revenueThisMonth >= $revenueLastMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $revenueColor = $revenueThisMonth >= $revenueLastMonth ? 'success' : 'danger';

        // Facturas por Pagar (Cantidad)
        $unpaidInvoicesCount = \App\Models\Invoice::whereIn('status', [
                \App\Enums\InvoiceStatus::INVOICED,
                \App\Enums\InvoiceStatus::OVERDUE,
                \App\Enums\InvoiceStatus::PARTIALLY_PAID
            ])->count();

        // Suscripciones Activas
        $activeSubscriptions = \App\Models\Subscription::where('status', \App\Enums\SubscriptionStatus::ACTIVE)->count();

        // Proyectos Activos (Tareas pendientes)
        $activeProjects = \App\Models\Project::whereIn('status', [
            \App\Enums\ProjectStatus::PLANNING,
            \App\Enums\ProjectStatus::DEVELOPMENT,
            \App\Enums\ProjectStatus::QA,
        ])->count();
        
        // 5. Horas de Soporte (Mes)
        $hoursThisMonth = \App\Models\WorkLog::whereMonth('worked_at', now()->month)
            ->whereYear('worked_at', now()->year)
            ->sum('hours');

        // 6. Total Clientes
        $totalClients = \App\Models\Client::count();

        // 7. Ticket Promedio (Facturas Pagadas)
        $averageTicket = \App\Models\Invoice::where('status', \App\Enums\InvoiceStatus::PAID)->avg('total') ?? 0;

        // 8. Proyectos Completados
        $completedProjects = \App\Models\Project::where('status', \App\Enums\ProjectStatus::DONE)->count();

        return [
            Stat::make('Ingresos (Mes)', '$' . number_format($revenueThisMonth, 2))
                ->description($revenueThisMonth >= $revenueLastMonth ? 'vs mes anterior' : 'vs mes anterior')
                ->descriptionIcon($revenueIcon)
                ->color($revenueColor)
                ->chart([$revenueLastMonth, $revenueThisMonth]),

            Stat::make('Facturas por Cobrar', $unpaidInvoicesCount)
                ->description('Documentos pendientes')
                ->descriptionIcon('heroicon-m-document-currency-dollar')
                ->color('info'),

            Stat::make('Suscripciones Activas', $activeSubscriptions)
                ->description('Ingresos recurrentes')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Proyectos en Curso', $activeProjects)
                ->description('Trabajos activos')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),

            // Fila 2
            Stat::make('Horas Soporte (Mes)', $hoursThisMonth . ' hrs')
                ->description('Tiempo registrado')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Total Clientes', $totalClients)
                ->description('Cartera activa')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Ticket Promedio', '$' . number_format($averageTicket, 2))
                ->description('Valor medio por factura')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('Proyectos Completados', $completedProjects)
                ->description('Histórico finalizado')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
