<?php

namespace App\Models;

use App\Scopes\Scoper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeWithScopes(Builder $builder, $request, $scopes = [])
    {
        return (new Scoper($request))->apply($builder, $scopes);
    }
}
