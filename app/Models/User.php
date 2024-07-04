<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    /**
     * @var string[]
     */
    protected $appends = [
        'full_name',
        'full_name_and_role'
    ];

    /**
     * Get full name of user
     *
     * @return Attribute
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name . ' ' . $this->surname
        );
    }

    public function fullNameAndRole(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->full_name . ', ' . $this->role()->pluck('name')[0]
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return $this->role()->where('name', $role)->exists();
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isDeveloper(): bool
    {
        return $this->hasRole('developer');
    }

    /**
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->hasRole('project manager');
    }

    /**
     * @return bool
     */
    public function isRegularUser(): bool
    {
        return $this->hasRole('user');
    }

    public function scopeWithRoles($query, $roles)
    {
        return $query->whereHas('role', function ($q) use ($roles) {
            $q->whereIn('name', (array) $roles);
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->team()->exists() && $this->hasVerifiedEmail();
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->team;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->team()->whereKey($tenant)->exists();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
