<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Expense;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class ExpenseYearlyChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Wydatki';

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $startDate = $this->filters && $this->filters['startDate'] ? Carbon::parse($this->filters['startDate']) : Carbon::now()->startOfYear();
        $endDate = $this->filters && $this->filters['endDate'] ? Carbon::parse($this->filters['endDate']) : Carbon::now()->endOfYear();

        $data = Trend::model(Expense::class)
            ->dateColumn('date')
            ->dateAlias('date_sum')
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->perMonth()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'zł wydatku',
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
