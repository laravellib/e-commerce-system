<?php

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingMethod::create([
            'name' => 'UPS',
            'price' => 1000,
        ]);

        ShippingMethod::create([
            'name' => 'Royal Mail 1st Class',
            'price' => 1000,
        ]);

        ShippingMethod::create([
            'name' => 'Royal Mail 2nd Class',
            'price' => 500,
        ]);

        ShippingMethod::create([
            'name' => 'China Mail Delivery',
            'price' => 500,
        ]);

        ShippingMethod::create([
            'name' => 'World Mail',
            'price' => 500,
        ]);
    }
}
