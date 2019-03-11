<?php

namespace App\Cart;

use App\Models\User;
use Illuminate\Support\Collection;

class Cart
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add(array $products)
    {
        $this->user->cart()->syncWithoutDetaching(
            $this->getStorePayload($products)
        );
    }

    public function update(int $id, int $quantity)
    {
        $this->user->cart()->updateExistingPivot($id, compact('quantity'));
    }

    public function delete(int $id)
    {
        $this->user->cart()->detach($id);
    }

    public function clear()
    {
        $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    private function getStorePayload(array $products): Collection
    {
        return collect($products)->mapWithKeys(function ($product) {
            return [
                $product['id'] => [
                    'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
                ]
            ];
        });
    }

    protected function getCurrentQuantity(int $productId)
    {
        $product = $this->user->cart()->where('id', $productId)->first();

        return $product ? $product->pivot->quantity : 0;
    }
}
