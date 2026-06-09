<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Accessors & Mutators ─────────────────────────────────────────────────

    /**
     * Auto-capitalize name on write; format it nicely on read.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value,
            set: fn(string $value) => ucwords(strtolower(trim($value))),
        );
    }

    /**
     * Returns the profile image URL, falling back to a generated avatar.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
                    return Storage::disk('public')->url($this->avatar);
                }
                $name = urlencode($this->attributes['name'] ?? 'User');
                return "https://ui-avatars.com/api/?name={$name}&background=0d6efd&color=fff&size=80&bold=true";
            },
        )->shouldCache();
    }

    /**
     * Returns the first Spatie role in title-case.
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn() => ucfirst($this->getRoleNames()->first() ?? 'user'),
        );
    }
}
