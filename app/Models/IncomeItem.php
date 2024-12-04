<?php

namespace App\Models;

use App\Models\Scopes\IncomeItemScope;
use App\Observers\IncomeItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

#[ObservedBy(IncomeItemObserver::class)]
class IncomeItem extends Model implements Sortable
{
    use SortableTrait;

    protected $fillable = [
        'title',
        'quantity',
        'price',
        'amount',
        'order_column',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'amount' => 'decimal:2',
        ];
    }
}
