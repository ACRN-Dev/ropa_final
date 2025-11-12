<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        'user_type',          // 0 = user, 1 = admin
        'department',         // nullable
        'job_title',          // nullable
        'active', 
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at', // added 2FA
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
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean', 
            'two_factor_expires_at' => 'datetime',// cast 2FA to boolean
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 1;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Relationship: User has many ROPAs
     */
    public function ropas()
    {
        return $this->hasMany(Ropa::class);
    }
}
