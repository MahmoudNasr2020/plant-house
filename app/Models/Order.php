<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'reference', 'customer_id', 'subtotal', 'shipping_fee',
        'discount_amount', 'total', 'payment_method', 'status',
        'city', 'address', 'coupon_code', 'notes',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'shipping_fee'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    // ── Auto-reference ───────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->reference)) {
                $last = static::max('id') ?? 3880;
                $order->reference = 'PH-' . ($last + 1);
            }
        });
    }

    // ── Relations ────────────────────────────────────────────────
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Accessors ────────────────────────────────────────────────
    public function getCustomerNameAttribute(): string
    {
        return $this->customer?->name ?? '—';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '⏳ معلق',
            'processing' => '🔄 معالجة',
            'shipped'    => '🚚 في الشحن',
            'delivered'  => '✅ مكتمل',
            'cancelled'  => '❌ ملغي',
            default      => $this->status,
        };
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }
}
