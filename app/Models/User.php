<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use App\Models\Invoice;
use App\Models\Expense;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $appends = ['storage_used', 'storage_used_for_humans'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'seller_name',
        'seller_tax_id',
        'seller_country',
        'seller_city',
        'seller_postal_code',
        'seller_address',
        'seller_email',
        'seller_phone',
        'limit_category',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (app()->environment('local')) {
            return true;
        }

        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@quiksite.pl') && $this->hasVerifiedEmail();
        }

        return true;
    }

    /**
     * Get file sizes of all invoices and expenses.
     *
     * @return integer
     */
    public function getStorageUsedAttribute(): int
    {
        $invoiceFilesSize = Invoice::all()->flatMap(function ($invoice) {
            return $invoice->getMedia();
        })->sum(function (Media $media) {
            return $media->size;
        });

        $expenseFilesSize = Expense::all()->flatMap(function ($expense) {
            return $expense->getMedia();
        })->sum(function (Media $media) {
            return $media->size;
        });

        return $invoiceFilesSize + $expenseFilesSize;
    }

    /**
     * Get file sizes of all invoices and expenses in human readable format.
     *
     * @return string
     */
    public function getStorageUsedForHumansAttribute(): string
    {
        $bytes = $this->storage_used;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
