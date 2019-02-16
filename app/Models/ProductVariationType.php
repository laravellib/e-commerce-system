<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationType extends Model
{
    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'id', 'type_id');
    }
}
