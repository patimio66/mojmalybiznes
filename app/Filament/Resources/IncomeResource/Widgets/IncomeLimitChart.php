<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Income;
use App\Models\MonthlyIncomeLimit;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class IncomeLimitChart extends ChartWidget
{
    protected static ?string $heading = 'Limit przychodów w tym miesiącu';

    protected int | string | array $columnSpan = ['default' => 12, 'lg' => 6];
    protected static ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $thisMonth = Income::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        $monthlyLimit = MonthlyIncomeLimit::where('starts_at', '>=', now()->startOfMonth())->where(function ($query) {
            $query->where('ends_at', '<=', now()->endOfMonth())->orWhereNull('ends_at');
        })
            ->first()
            ?->{auth()->user()?->limit_category ?? 'default'} ?? throw new \Exception('Brak limitu przychodów dla tego miesiąca');

        return [
            'datasets' => [
                [
                    'data' => [$thisMonth, $monthlyLimit - $thisMonth],
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
