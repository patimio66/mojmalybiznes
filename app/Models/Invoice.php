<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'invoice_id', // self (parent)
        'invoice_number',
        'issue_date',
        'transaction_date',
        'due_date',
        'description',
        'amount',
        'tax_exemption_type',
        'is_paid',
        'contractor_id',
        // contractor fixed data
        'contractor_name',
        'contractor_tax_id',
        'contractor_country',
        'contractor_city',
        'contractor_postal_code',
        'contractor_address',
        'contractor_email',
        'contractor_phone',
        // seller fixed data
        'seller_name',
        'seller_tax_id',
        'seller_country',
        'seller_city',
        'seller_postal_code',
        'seller_address',
        'seller_email',
        'seller_phone',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'transaction_date' => 'date',
            'due_date' => 'date',
            'amount' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class);
    }

    /**
     * Parent relationship: this invoice belongs to another invoice
     * Usage: $parent = $invoice->parent;
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Child relationship: this invoice has child invoices
     * Usage: $children = $invoice->children;
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_id');
    }

    public function updateTotal(): bool
    {
        $amount = $this->calculateTotal($this->items);
        return $this->update(['amount' => $amount]);
    }

    public static function calculateTotal(Collection $items): float
    {
        return $items->reduce(function ($subtotal, $invoiceItem) {
            if (is_array($invoiceItem)) {
                return $subtotal + round((((float)$invoiceItem['price'] ?? 0) * ((float)$invoiceItem['quantity'] ?? 0)), 2);
            } else {
                return $subtotal + ((float)$invoiceItem->amount ?? 0);
            }
        }, 0);
    }
}
