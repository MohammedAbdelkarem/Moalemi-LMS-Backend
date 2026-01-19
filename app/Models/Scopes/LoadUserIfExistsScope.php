<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LoadUserIfExistsScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * This scope will eager load the user relation only for coupons that have a user_id.
     * Laravel automatically handles null foreign keys efficiently by not querying for them.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Eager load user relation - Laravel will automatically skip loading
        // for coupons where user_id is null, making this efficient
        $builder->with('coponLogs.user');
    }
}
