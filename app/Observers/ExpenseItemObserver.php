<?php

namespace App\Observers;

use App\Models\ExpenseItem;

class ExpenseItemObserver
{
    /**
     * Handle the ExpenseItem "creating" event.
     */
    public function creating(ExpenseItem $expenseItem): void
    {
        $expenseItem->amount = ExpenseItem::calculateTotal($expenseItem->price, $expenseItem->quantity);
    }

    /**
     * Handle the ExpenseItem "created" event.
     */
    public function created(ExpenseItem $expenseItem): void
    {
        $expenseItem->expense->updateTotal();
    }

    /**
     * Handle the ExpenseItem "updating" event.
     */
    public function updating(ExpenseItem $expenseItem): void
    {
        $expenseItem->amount = ExpenseItem::calculateTotal($expenseItem->price, $expenseItem->quantity);
    }

    /**
     * Handle the ExpenseItem "updated" event.
     */
    public function updated(ExpenseItem $expenseItem): void
    {
        $expenseItem->expense->updateTotal();
    }

    /**
     * Handle the ExpenseItem "deleted" event.
     */
    public function deleted(ExpenseItem $expenseItem): void
    {
        //
    }

    /**
     * Handle the ExpenseItem "restored" event.
     */
    public function restored(ExpenseItem $expenseItem): void
    {
        //
    }

    /**
     * Handle the ExpenseItem "force deleted" event.
     */
    public function forceDeleted(ExpenseItem $expenseItem): void
    {
        //
    }
}
