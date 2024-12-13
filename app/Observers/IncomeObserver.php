<?php

namespace App\Observers;

use App\Models\Income;
use Illuminate\Support\Facades\Auth;

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
        //
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
        //
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
