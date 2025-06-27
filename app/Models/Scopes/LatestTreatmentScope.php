<?php

namespace App\Models\Scopes;

use App\Enums\TreatmentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class LatestTreatmentScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('is_latest', 1)
        ->where('status' , '!=' , TreatmentStatusEnum::EXPIRED->value)
        ->with('userable');
    }
}
