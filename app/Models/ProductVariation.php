<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasPrice;

    public function getPriceAttribute($value)
    {
        if ($value === null) {
            return $this->product->price;
        }

        return $value;
    }

    public function priceVaries(): bool
    {
        return !$this->money->equals($this->product->money);
    }

    public function type()
    {
        return $this->belongsTo(ProductVariationType::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stock()
    {
        return $this->belongsToMany(self::class, 'product_variation_stock_view')
            ->withPivot(['stock', 'in_stock']);
    }

    public function stockCount()
    {
        return $this->stock->first()->pivot->stock;
    }

    public function inStock(): bool
    {
        return $this->stockCount() > 0;
    }
}
