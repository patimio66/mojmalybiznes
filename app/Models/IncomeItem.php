<?php

namespace App\Models;

use App\Observers\IncomeItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

#[ObservedBy(IncomeItemObserver::class)]
class IncomeItem extends Model implements Sortable
{
    /** @use HasFactory<\Database\Factories\IncomeItemFactory> */
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

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class);
    }
}
