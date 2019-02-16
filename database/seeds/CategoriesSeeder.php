<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = factory(Category::class, 5)->create([
            'parent_id' => null,
        ]);

        $categories->each(function ($category) {
            factory(Category::class, random_int(0, 5))->create([
                'parent_id' => $category->id
            ]);
        });
    }
}
