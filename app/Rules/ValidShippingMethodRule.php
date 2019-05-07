<?php

namespace App\Rules;

use App\Models\Address;
use Illuminate\Contracts\Validation\Rule;

class ValidShippingMethodRule implements Rule
{
    private $addressId;

    /**
     * Create a new rule instance.
     *
     * @param $addressId
     */
    public function __construct($addressId)
    {
        $this->addressId = $addressId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (!$this->addressId) {
            return false;
        }

        $address = $this->getAddress();

        if (!$address) {
            return false;
        }

        return $address->country->shippingMethods->contains('id', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Invalid shipping method.';
    }

    private function getAddress()
    {
        return Address::find($this->addressId);
    }
}
