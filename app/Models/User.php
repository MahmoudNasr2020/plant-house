<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function getRoleLabelAttribute(): string
    {
        $role = Role::where('key', $this->role)->first();

        return $role?->label ?? match ($this->role) {
            'super_admin' => 'مدير عام',
            'admin'       => 'مدير',
            'manager'     => 'مشرف',
            default       => $this->role,
        };
    }

    public function roleModel(): ?Role
    {
        return Role::where('key', $this->role)->first();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $role = $this->roleModel();

        if (!$role) {
            return false;
        }

        return in_array($permission, $role->permissions ?? [], true);
    }
}
