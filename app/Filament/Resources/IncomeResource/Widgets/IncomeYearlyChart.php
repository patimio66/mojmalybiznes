<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Income;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class IncomeYearlyChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Przychody';

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $startDate = Carbon::parse($this->filters['startDate']) ?? Carbon::now()->startOfYear();
        $endDate = Carbon::parse($this->filters['endDate']) ?? Carbon::now()->endOfYear();

        $data = Trend::model(Income::class)
            ->dateColumn('date')
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->perMonth()
            ->sum('amount');

        // dd(Income::whereBetween(
        //     'date',
        //     [
        //         $startDate,
        //         $endDate
        //     ]
        // )->sum('amount'));

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
