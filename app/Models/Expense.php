<?php

namespace App\Models;

use App\Observers\ExpenseObserver;
use Illuminate\Support\Collection;
use App\Models\Scopes\UserAccessScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy(ExpenseObserver::class)]
#[ScopedBy(UserAccessScope::class)]
class Expense extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'contractor_id',
        'user_id',
        'title',
        'amount',
        'date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function updateTotal(): bool
    {
        $amount = $this->calculateTotal($this->items);
        return $this->update(['amount' => $amount]);
    }

    public static function calculateTotal(Collection $items): float
    {
        return $items->reduce(function ($subtotal, $expenseItem) {
            if (is_array($expenseItem)) {
                return $subtotal + round((((float)$expenseItem['price'] ?? 0) * ((float)$expenseItem['quantity'] ?? 0)), 2);
            } else {
                return $subtotal + ((float)$expenseItem->amount ?? 0);
            }
        }, 0);
    }
}
