<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
class Project extends Model
{
    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'client_id',
        'git_repository',
        'project_image'
    ];

    /**
     * Relationship method for teams
     *
     * @return BelongsToMany
     */
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'project_team', 'project_id', 'team_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

//    protected static function booted(): void
//    {
//        static::addGlobalScope('team', function (Builder $query) {
//            if (auth()->hasUser()) {
//                $query->where('team_id', auth()->user()->teams()->pluck('id'));
//            }
//        });
//    }
}
