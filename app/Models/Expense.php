<?php

namespace App\Models;

use App\Observers\ExpenseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[ObservedBy(ExpenseObserver::class)]
class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'date',
        'description',
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

    public function updateTotal(): bool
    {
        $amount = $this->calculateTotal($this->items);
        return $this->update(['amount' => $amount]);
    }

    public static function calculateTotal(Collection $items): float
    {
        return $items->reduce(function ($subtotal, $expenseItem) {
            if (is_array($expenseItem)) {
                return $subtotal + round((($expenseItem['price'] ?? 0) * ((int) $expenseItem['quantity'] ?? 0)), 2);
            } else {
                return $subtotal + ($expenseItem->amount ?? 0);
            }
        }, 0);
    }
}
