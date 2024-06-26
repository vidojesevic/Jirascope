<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TeamScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();
        if ($user) {
            $builder->where('team_id', $user->team_id);
        }
    }
}
