<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'city', 'address', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relations ────────────────────────────────────────────────
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // ── Accessors ────────────────────────────────────────────────
    public function getOrdersCountAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', 'delivered')->sum('total');
    }
}
