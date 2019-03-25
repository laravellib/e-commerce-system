<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariationType;
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
        $this->call(CountriesTableSeeder::class);

//        TODO: feature base database population
//        $categories = factory(Category::class, 5)->create();
//
//        $this->createVariationTypes();
//
//        $categories->each(function ($category) {
//            $this->createSubCategoriesWithProducts($category);
//        });
    }

    /**
     * @param $category
     * @throws Exception
     */
    public function createSubCategoriesWithProducts($category): void
    {
        $subCategories = factory(Category::class, random_int(0, 5))->make();

        $category->children()->saveMany($subCategories);

        $subCategories->each(function ($category) {
            $this->createProductsForCategory($category);
        });
    }

    /**
     * @param $category
     * @throws Exception
     */
    private function createProductsForCategory($category)
    {
        $products = factory(Product::class, random_int(0, 5))->make();

        $category->products()->saveMany($products);
    }

    private function createVariationTypes(): void
    {
        $sizeType = factory(ProductVariationType::class)->create([
            'name' => 'size'
        ]);
    }
}
