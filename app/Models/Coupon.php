<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'usage_count', 'usage_limit',
        'min_order_amount', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'value'             => 'decimal:2',
        'min_order_amount'  => 'decimal:2',
        'expires_at'        => 'date',
        'is_active'         => 'boolean',
        'usage_count'       => 'integer',
        'usage_limit'       => 'integer',
    ];

    // ── Helpers ──────────────────────────────────────────────────
    public function getTypeLabel(): string
    {
        return $this->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت';
    }

    public function getStatusLabel(): string
    {
        if (!$this->is_active) return 'معطل';
        if ($this->expires_at && $this->expires_at->isFuture()) return 'قادم';
        if ($this->expires_at && $this->expires_at->isPast()) return 'منتهي';
        return 'نشط';
    }

    public function isValid(float $orderTotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        if ($orderTotal < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'percentage') {
            return round($orderTotal * ($this->value / 100), 2);
        }
        return min($this->value, $orderTotal);
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now()));
    }
}
