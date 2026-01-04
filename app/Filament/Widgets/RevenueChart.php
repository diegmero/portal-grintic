<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Support\Colors\Color;

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
                    'borderColor' => 'rgb(' . Color::Green[500] . ')',
                    'backgroundColor' => 'rgba(' . Color::Green[500] . ', 0.1)',
                    'pointBackgroundColor' => 'rgb(' . Color::Green[500] . ')',
                    'pointBorderColor' => 'rgb(' . Color::Green[500] . ')',
                    'pointHoverBackgroundColor' => 'rgb(' . Color::Green[500] . ')',
                    'pointHoverBorderColor' => 'rgb(' . Color::Green[500] . ')',
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
