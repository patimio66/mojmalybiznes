<?php

namespace App\Models;

use Spatie\EloquentSortable\Sortable;
use App\Observers\ExpenseItemObserver;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

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

    public static function calculateTotal(null|float|string $price = 0, null|float|string $quantity = 0): float
    {
        return round(((float)$price * (float)$quantity), 2);
    }
}
