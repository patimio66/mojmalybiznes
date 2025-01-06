<?php

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class InvoiceObserver
{
    /**
     * Handle the Invoice "creating" event.
     */
    public function creating(Invoice $invoice): void
    {
        if (Auth::user()->storage_used >= (1024 * 1024 * 1024)) {
            Notification::make()
                ->title('Przekroczono limit miejsca!')
                ->danger()
                ->send();
            throw ValidationException::withMessages(['Przekroczono limit miejsca']);
        }
        $invoice->user_id = Auth::user()->id;
        $invoice->seller_name ??= Auth::user()->name;
    }

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
