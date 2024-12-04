<?php

namespace App\Observers;

use App\Models\IncomeItem;

class IncomeItemObserver
{
    /**
     * Handle the IncomeItem "creating" event.
     */
    public function creating(IncomeItem $incomeItem): void
    {
        $incomeItem->amount = $incomeItem->quantity * $incomeItem->price;
    }

    /**
     * Handle the IncomeItem "created" event.
     */
    public function created(IncomeItem $incomeItem): void
    {
        //
    }

    /**
     * Handle the IncomeItem "updated" event.
     */
    public function updated(IncomeItem $incomeItem): void
    {
        //
    }

    /**
     * Handle the IncomeItem "deleted" event.
     */
    public function deleted(IncomeItem $incomeItem): void
    {
        //
    }

    /**
     * Handle the IncomeItem "restored" event.
     */
    public function restored(IncomeItem $incomeItem): void
    {
        //
    }

    /**
     * Handle the IncomeItem "force deleted" event.
     */
    public function forceDeleted(IncomeItem $incomeItem): void
    {
        //
    }
}
