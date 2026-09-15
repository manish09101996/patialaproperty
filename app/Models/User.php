<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'google_id',
        'role',
        'is_active',
        'profile_picture',
        'email_verified_at',
        'mobile_verified_at',
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
            'mobile_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role Helper Methods
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isServiceProvider(): bool
    {
        return $this->role === 'service_provider';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    // Relationships
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function savedProperties()
    {
        return $this->hasMany(SavedProperty::class);
    }

    public function leadsAsBuyer()
    {
        return $this->hasMany(PropertyLead::class, 'user_id');
    }

    public function leadsAsOwner()
    {
        return $this->hasMany(PropertyLead::class, 'owner_id');
    }

    public function visits()
    {
        return $this->hasMany(PropertyVisit::class);
    }
}
