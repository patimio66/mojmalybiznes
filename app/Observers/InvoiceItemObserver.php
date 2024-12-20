<?php

namespace App\Observers;

use App\Models\InvoiceItem;

class InvoiceItemObserver
{
    /**
     * Handle the InvoiceItem "created" event.
     */
    public function created(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "updated" event.
     */
    public function updated(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "deleted" event.
     */
    public function deleted(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "restored" event.
     */
    public function restored(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "force deleted" event.
     */
    public function forceDeleted(InvoiceItem $invoiceItem): void
    {
        //
    }
}
