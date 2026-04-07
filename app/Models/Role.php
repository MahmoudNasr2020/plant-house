<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'key', 'label', 'emoji', 'description', 'permissions', 'is_system', 'sort_order',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role', 'key');
    }

    public function getUsersCountAttribute(): int
    {
        return User::where('role', $this->key)->count();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
