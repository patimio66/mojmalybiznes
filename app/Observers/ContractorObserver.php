<?php

namespace App\Observers;

use App\Models\Contractor;
use Illuminate\Support\Facades\Auth;

class ContractorObserver
{
    /**
     * Handle the Contractor "creating" event.
     */
    public function creating(Contractor $contractor): void
    {
        $contractor->user_id = Auth::user()->id;
    }

    /**
     * Handle the Contractor "created" event.
     */
    public function created(Contractor $contractor): void
    {
        //
    }

    /**
     * Handle the Contractor "updated" event.
     */
    public function updated(Contractor $contractor): void
    {
        //
    }

    /**
     * Handle the Contractor "deleted" event.
     */
    public function deleted(Contractor $contractor): void
    {
        //
    }

    /**
     * Handle the Contractor "restored" event.
     */
    public function restored(Contractor $contractor): void
    {
        //
    }

    /**
     * Handle the Contractor "force deleted" event.
     */
    public function forceDeleted(Contractor $contractor): void
    {
        //
    }
}
