<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'disabled_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'user_details_id' => 'integer:nullable',
            'disabled_at' => 'datetime',
        ];
    }

    public function isDisabled()
    {
        return !is_null($this->disabled_at);
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function catalogs()
    {
        return $this->hasOne(Catalog::class);
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role->role_name, $roles);
    }

    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return strtolower($this->role->role_name) === strtolower($role);
        }
        
        if ($role instanceof Role) {
            return $this->role->id === $role->id;
        }
        
        return false;
    }
}
