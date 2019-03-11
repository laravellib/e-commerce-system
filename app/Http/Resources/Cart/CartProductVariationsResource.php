<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductVariationResource;
use App\Money\Money;

class CartProductVariationsResource extends ProductVariationResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'product' => new ProductIndexResource($this->product),
            'quantity' => $this->pivot->quantity,
            'total' => $this->getTotal()->formatted()
        ]);
    }

    /**
     * @return Money
     */
    private function getTotal(): Money
    {
        return new Money($this->pivot->quantity * $this->money->amount());
    }
}
