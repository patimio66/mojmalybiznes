<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Set;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->label('Filtry')
                ->modalHeading('Filtry')
                ->modalSubmitActionLabel('Zaakceptuj')
                ->form([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\DatePicker::make('startDate')->label('Początek')->default(now()->startOfYear()),
                            Forms\Components\DatePicker::make('endDate')->label('Koniec')->default(now()->endOfYear()),
                        ])
                        ->columns(2),
                ]),
        ];
    }
}
