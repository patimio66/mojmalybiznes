<?php

namespace App\Models;

use App\Observers\ExpenseItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

#[ObservedBy(ExpenseItemObserver::class)]
class ExpenseItem extends Model implements Sortable
{
    /** @use HasFactory<\Database\Factories\ExpenseItemFactory> */
    use HasFactory, SortableTrait;

    protected $fillable = [
        'title',
        'quantity',
        'uom',
        'price',
        'amount',
        'order_column',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'price' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public static function calculateTotal(float $price = 0, float $quantity = 0): float
    {
        return round(($price * $quantity), 2);
    }
}
