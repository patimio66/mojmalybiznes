<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomeLimitChart extends ChartWidget
{
    protected static ?string $heading = 'Limit przychodów w tym miesiącu';

    protected int | string | array $columnSpan = ['default' => 12, 'lg' => 6];
    protected static ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $thisMonthIncomeLimit = auth()->user()->getMonthlyIncomeLimit(now());
        $thisMonthIncome = auth()->user()->getMonthlyIncome(now());

        return [
            'datasets' => [
                [
                    'data' => [$thisMonthIncome, $thisMonthIncomeLimit - $thisMonthIncome],
                    'backgroundColor' => [
                        'rgba(' . Color::Emerald[500] . ', 0.75)',
                        'rgba(' . Color::Gray[500] . ', 0.75)',
                    ]
                ],
            ],
            'labels' => ['zł wykorzystane', 'zł wolne'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array|\Filament\Support\RawJs|null
    {
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }
}
