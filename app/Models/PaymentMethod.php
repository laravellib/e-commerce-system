<?php

namespace App\Models;

use App\Models\Traits\CanBeDefault;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use CanBeDefault;

    protected $fillable = [
        'default',
        'card_type',
        'last_four',
        'provider_id',
    ];

    protected $casts = [
        'default' => 'bool',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
