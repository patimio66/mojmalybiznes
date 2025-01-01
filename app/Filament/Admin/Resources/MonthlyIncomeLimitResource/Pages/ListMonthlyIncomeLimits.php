<?php

namespace App\Filament\Admin\Resources\MonthlyIncomeLimitResource\Pages;

use App\Filament\Admin\Resources\MonthlyIncomeLimitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyIncomeLimits extends ListRecords
{
    protected static string $resource = MonthlyIncomeLimitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
