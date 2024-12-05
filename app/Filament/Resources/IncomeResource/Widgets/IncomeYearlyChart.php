<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class IncomeYearlyChart extends ChartWidget
{
    protected static ?string $heading = 'Przychody w skali roku';

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $data = Trend::model(Income::class)
            ->dateColumn('date')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'zł przychodu',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
