<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = [
        'name',
        'description',
        'street',
        'number',
        'country',
        'phone',
        'city',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
