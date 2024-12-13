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
        $incomeItem->amount = round((($incomeItem->price ?? 0) * ((int) $incomeItem->quantity ?? 0)), 2);
    }

    /**
     * Handle the IncomeItem "created" event.
     */
    public function created(IncomeItem $incomeItem): void
    {
        $incomeItem->income->updateTotals();
    }

    /**
     * Handle the IncomeItem "updating" event.
     */
    public function updating(IncomeItem $incomeItem): void
    {
        $incomeItem->amount = round((($incomeItem->price ?? 0) * ((int) $incomeItem->quantity ?? 0)), 2);
    }

    /**
     * Handle the IncomeItem "updated" event.
     */
    public function updated(IncomeItem $incomeItem): void
    {
        $incomeItem->income->updateTotals();
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
