<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyIncomeLimit extends Model
{
    /** @use HasFactory<\Database\Factories\MonthlyIncomeLimitFactory> */
    use HasFactory;

    protected $fillable = [
        'default',
        'unemployed',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'default' => 'decimal:2',
            'unemployed' => 'decimal:2',
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }
}
