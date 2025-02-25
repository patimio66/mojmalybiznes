<?php

namespace App\Models;

use App\Observers\IncomeObserver;
use Illuminate\Support\Collection;
use App\Models\Scopes\UserAccessScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(IncomeObserver::class)]
#[ScopedBy(UserAccessScope::class)]
class Income extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeFactory> */
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'user_id',
        'title',
        'amount',
        'date',
        'notes',
        'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
            'is_paid' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(IncomeItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function updateTotal(): bool
    {
        $amount = $this->calculateTotal($this->items);
        return $this->update(['amount' => $amount]);
    }

    public static function calculateTotal(Collection $items): float
    {
        return round($items->reduce(function ($subtotal, $incomeItem) {
            if (is_array($incomeItem)) {
                return $subtotal + round((((float)$incomeItem['price'] ?? 0) * ((float)$incomeItem['quantity'] ?? 0)), 2);
            } else {
                return $subtotal + round(((float)$incomeItem->amount ?? 0), 2);
            }
        }, 0), 2);
    }
}
