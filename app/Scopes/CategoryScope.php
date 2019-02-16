<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;

class CategoryScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        return $builder->whereHas('categories', function ($builder) use ($value) {
            return $builder->where('slug', $value);
        });
    }
}
