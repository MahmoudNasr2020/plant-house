<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'brand', 'name', 'slug', 'description',
        'price', 'old_price', 'image_url', 'badge', 'discount',
        'rating', 'reviews_count', 'stock', 'is_active',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'old_price'     => 'decimal:2',
        'rating'        => 'decimal:1',
        'discount'      => 'integer',
        'reviews_count' => 'integer',
        'stock'         => 'integer',
        'is_active'     => 'boolean',
    ];

    // ── Auto-slug ────────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name . '-' . $product->brand);
            }
        });
    }

    // ── Relations ────────────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Accessors ────────────────────────────────────────────────
    public function getCategoryLabelAttribute(): string
    {
        return $this->category?->name ?? '—';
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->discount > 0 && $this->old_price !== null;
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('discount', '>', 0);
    }

    public function scopeByCategory($query, string $slug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }
}
