<?php

namespace App\Models\Traits;

use App\Scopes\Scoper;
use Illuminate\Database\Eloquent\Builder;

trait HasScopes
{
    public function scopeWithScopes(Builder $builder, $request, $scopes = []): Builder
    {
        return (new Scoper($request))->apply($builder, $scopes);
    }
}
