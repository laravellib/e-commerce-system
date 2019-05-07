<?php

use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCountries();
        $this->seedShippingMethods();

        $this->seedCategories();
        $this->seedVariationTypes();
        $this->seedProducts();
        $this->seedProductsVariations();

        $this->seedUser();
    }

    private function seedProducts()
    {
        Category::each(function (Category $category) {
            $category->products()->saveMany(
                factory(Product::class, random_int(0, 5))->make()
            );
        });
    }

    private function seedVariationTypes(): void
    {
        factory(ProductVariationType::class)->create([
            'name' => 'size'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'color'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'class'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'weight'
        ]);
    }

    private function seedCountries(): void
    {
        $this->call(CountriesTableSeeder::class);
    }

    private function seedShippingMethods(): void
    {
        $this->call(ShippingMethodsSeeder::class);

        /** @var \Illuminate\Support\Collection $shippings */
        $shippings = ShippingMethod::get();

        Country::each(function ($country) use ($shippings) {
            $country->shippingMethods()->attach(
                $shippings->random(2)
            );
        });
    }

    private function seedCategories()
    {
        $categories = factory(Category::class, 5)->create();

        $categories->each(function (Category $category) {
            $category->children()->saveMany(
                factory(Category::class, random_int(0, 5))->make()
            );
        });
    }

    private function seedProductsVariations()
    {
        /** @var \Illuminate\Support\Collection $types */
        $types = ProductVariationType::get();

        Product::each(function (Product $product) use ($types) {
            $product->variations()->saveMany(
                $variations = factory(ProductVariation::class, random_int(0, 5))->create([
                    'type_id' => $types->random()->id,
                    'product_id' => $product->id
                ])
            );

            $variations->each(function ($variation) {
                $variation->stocks()->save(
                    factory(Stock::class)->make([
                        'quantity' => random_int(0, 10)
                    ])
                );
            });
        });
    }

    private function seedUser()
    {
        factory(User::class)->create([
            'email' => env('ROOT_USER_EMAIL'),
            'password' => env('ROOT_USER_PASSWORD'),
        ]);
    }
}
