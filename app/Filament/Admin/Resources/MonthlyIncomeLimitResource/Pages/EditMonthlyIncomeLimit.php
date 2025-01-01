<?php

namespace App\Filament\Admin\Resources\MonthlyIncomeLimitResource\Pages;

use App\Filament\Admin\Resources\MonthlyIncomeLimitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonthlyIncomeLimit extends EditRecord
{
    protected static string $resource = MonthlyIncomeLimitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
