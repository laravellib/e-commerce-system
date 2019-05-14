<?php

namespace App\Models\Traits;

use App\Money\Money;
use Illuminate\Database\Eloquent\Model;

trait CanBeDefault
{
    protected static function bootCanBeDefault()
    {
        static::creating(function (Model $model) {
            if ($model->default) {
                $model->newQuery()->where('user_id', $model->user_id)->update([
                    'default' => false,
                ]);
            }
        });
    }
}
