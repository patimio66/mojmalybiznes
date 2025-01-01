<?php

namespace App\Observers;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class ExpenseObserver
{
    /**
     * Handle the Expense "creating" event.
     */
    public function creating(Expense $expense): void
    {
        if (Auth::user()->storage_used >= (1024 * 1024 * 1024)) {
            Notification::make()
                ->title('Przekroczono limit miejsca!')
                ->danger()
                ->send();
            throw ValidationException::withMessages(['Przekroczono limit miejsca']);
        }
        $expense->user_id = Auth::user()->id;
    }

    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "updating" event.
     */
    public function updating(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "restored" event.
     */
    public function restored(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "force deleted" event.
     */
    public function forceDeleted(Expense $expense): void
    {
        //
    }
}
