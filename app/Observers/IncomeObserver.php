<?php

namespace App\Observers;

use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class IncomeObserver
{
    /**
     * Handle the Income "creating" event.
     */
    public function creating(Income $income): void
    {
        $income->user_id = Auth::user()->id;
    }

    /**
     * Handle the Income "created" event.
     */
    public function created(Income $income): void
    {
        if (auth()->user()->getMonthlyIncome($income->date) > auth()->user()->getMonthlyIncomeLimit($income->date)) {
            Notification::make()
                ->danger()
                ->title('Zablokowano operację.')
                ->body('Operacja spowodowałaby przekroczenie miesięcznego limitu przychodu.')
                ->send();
            throw ValidationException::withMessages(['Przekroczono limit przychodu']);
        }
    }

    /**
     * Handle the Income "updating" event.
     */
    public function updating(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "updated" event.
     */
    public function updated(Income $income): void
    {
        if (auth()->user()->getMonthlyIncome($income->date) > auth()->user()->getMonthlyIncomeLimit($income->date)) {
            Notification::make()
                ->danger()
                ->title('Zablokowano operację.')
                ->body('Operacja spowodowałaby przekroczenie miesięcznego limitu przychodu.')
                ->send();
            throw ValidationException::withMessages(['Przekroczono limit przychodu']);
        }
    }

    /**
     * Handle the Income "deleted" event.
     */
    public function deleted(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "restored" event.
     */
    public function restored(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "force deleted" event.
     */
    public function forceDeleted(Income $income): void
    {
        //
    }
}
