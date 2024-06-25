<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class); //, 'team_user', 'user_id', 'team_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_team', 'team_id', 'project_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
