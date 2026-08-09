<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CONTENT_MANAGER = 'content_manager';
    public const ROLE_MARKETING = 'marketing';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * الوسائط التي أنشأها المسؤول.
     */
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * المقالات التي أنشأها المسؤول.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * التحقق من دور محدد.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * التحقق من امتلاك أحد الأدوار المحددة.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /**
     * التحقق من أن الحساب نشط.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * التحقق من أن المسؤول Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    /**
     * التحقق من أن المسؤول Admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * التحقق من أن المسؤول Content Manager.
     */
    public function isContentManager(): bool
    {
        return $this->hasRole(self::ROLE_CONTENT_MANAGER);
    }

    /**
     * التحقق من أن المسؤول Marketing.
     */
    public function isMarketing(): bool
    {
        return $this->hasRole(self::ROLE_MARKETING);
    }
}