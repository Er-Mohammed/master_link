<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

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



    protected $fillable = [

        'name',

        'email',

        'password',

        'role',

        'is_active',

    ];



    protected $hidden = [

        'password',

        'remember_token',

    ];



    protected function casts(): array
    {
        return [

            'is_active' => 'boolean',

        ];
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function media()
    {
        return $this->hasMany(Media::class);
    }



    public function posts()
    {
        return $this->hasMany(Post::class);
    }



    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */


    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }



    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }



    public function isActive(): bool
    {
        return $this->is_active;
    }

}