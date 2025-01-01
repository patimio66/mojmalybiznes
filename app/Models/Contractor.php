<?php

namespace App\Models;

use App\Observers\ContractorObserver;
use App\Models\Scopes\UserAccessScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(ContractorObserver::class)]
#[ScopedBy(UserAccessScope::class)]
class Contractor extends Model
{
    /** @use HasFactory<\Database\Factories\ContractorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'tax_id',
        'country',
        'city',
        'postal_code',
        'address',
        'email',
        'phone',
        'notes',
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
