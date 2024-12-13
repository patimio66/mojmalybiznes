<?php

namespace App\Models;

use App\Observers\IncomeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(IncomeObserver::class)]
class Income extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeFactory> */
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
        return $this->hasMany(IncomeItem::class);
    }

    public function updateTotals(): bool
    {
        $amount = $this->items->reduce(function ($subtotal, $incomeItem) {
            return $subtotal + ($incomeItem->amount ?? 0);
        }, 0);
        return $this->update(['amount' => $amount]);
    }
}
