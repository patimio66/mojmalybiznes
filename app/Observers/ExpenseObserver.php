<?php

namespace App\Observers;

use App\Models\Expense;

class ExpenseObserver
{
    /**
     * Handle the Expense "creating" event.
     */
    public function creating(Expense $expense): void
    {
        $expense->amount = $expense->items->reduce(function ($subtotal, $expenseItem) {
            return $subtotal + ($expenseItem->amount ?? 0);
        }, 0);
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
        $expense->amount = $expense->items->reduce(function ($subtotal, $expenseItem) {
            return $subtotal + ($expenseItem->amount ?? 0);
        }, 0);
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
