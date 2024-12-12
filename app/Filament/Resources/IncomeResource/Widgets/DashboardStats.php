<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Expense;
use App\Models\Income;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class DashboardStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters && $this->filters['startDate'] ? Carbon::parse($this->filters['startDate']) : Carbon::now()->startOfYear();
        $endDate = $this->filters && $this->filters['endDate'] ? Carbon::parse($this->filters['endDate']) : Carbon::now()->endOfYear();

        $incomes = Income::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $revenue = $incomes - $expenses;
        return [
            Stat::make('Przychód w okresie ' . $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y') . ':', $incomes . ' zł')
                ->description('Suma przychodów')
                ->color('success'),
            Stat::make('Wydatki w okresie ' . $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y') . ':', $expenses . ' zł')
                ->description('Suma wydatków')
                ->color('danger'),
            Stat::make('Dochód w okresie ' . $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y') . ':', $revenue . ' zł')
                ->description('Różnica: przychody - wydatki')
                ->color('warning'),
        ];
    }
}
