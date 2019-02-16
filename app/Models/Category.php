<?php

namespace App\Models;

use App\Models\Traits\{HasChildren, IsOrderable};
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasChildren, IsOrderable;

    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
