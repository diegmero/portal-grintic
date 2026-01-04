<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos Mensuales';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = \Flowframe\Trend\Trend::model(\App\Models\Invoice::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => 'Facturación',
                    'data' => $data->map(fn (\Flowframe\Trend\TrendValue $value) => $value->aggregate),
                    'borderColor' => '#F59E0B', // Amber-500
                    'backgroundColor' => '#FEF3C7', // Amber-100
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn (\Flowframe\Trend\TrendValue $value) => \Carbon\Carbon::parse($value->date)->translatedFormat('M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
