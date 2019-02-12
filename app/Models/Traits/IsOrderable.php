<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IsOrderable
{
    public function scopeOrdered(Builder $query, string $direction = 'asc')
    {
        return $query->orderBy('order', $direction);
    }
}
