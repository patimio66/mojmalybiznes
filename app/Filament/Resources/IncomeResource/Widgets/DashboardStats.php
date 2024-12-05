<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Expense;
use App\Models\Income;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->startOfYear();
        $endDate = $this->filters['endDate'] ?? now()->endOfYear();

        $incomes = Income::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $revenue = $incomes - $expenses;
        return [
            Stat::make('Przychód w wybranym okresie', $incomes . ' zł')
                ->description('Suma przychodów')
                ->color('success'),
            Stat::make('Wydatki w wybranym okresie', $expenses . ' zł')
                ->description('Suma wydatków')
                ->color('danger'),
            Stat::make('Dochód w wybranym okresie', $revenue . ' zł')
                ->description('Różnica: przychody - wydatki')
                ->color('warning'),
        ];
    }
}
