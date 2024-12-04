<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Income extends Model
{
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
}
