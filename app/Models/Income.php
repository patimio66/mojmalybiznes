<?php

namespace App\Models;

use App\Models\Scopes\UserAccessScope;
use App\Observers\IncomeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[ObservedBy(IncomeObserver::class)]
#[ScopedBy(UserAccessScope::class)]
class Income extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        return $this->hasMany(IncomeItem::class);
    }

    public function updateTotal(): bool
    {
        $amount = $this->calculateTotal($this->items);
        return $this->update(['amount' => $amount]);
    }

    public static function calculateTotal(Collection $items): float
    {
        return $items->reduce(function ($subtotal, $incomeItem) {
            if (is_array($incomeItem)) {
                return $subtotal + round((((float)$incomeItem['price'] ?? 0) * ((float)$incomeItem['quantity'] ?? 0)), 2);
            } else {
                return $subtotal + ((float)$incomeItem->amount ?? 0);
            }
        }, 0);
    }
}
