<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    /** @test */
    function it_returns_a_collection_of_categories()
    {
        $categories = factory(Category::class, 2)->create();

        $response = $this->getJson('api/categories');

        $response->assertJsonFragment([
            'name' => $categories[0]->name,
            'slug' => $categories[0]->slug,
        ]);

        $response->assertJsonFragment([
            'name' => $categories[1]->name,
            'slug' => $categories[1]->slug,
        ]);
    }

    /** @test */
    function it_returns_parents_categories_on_top_level()
    {
        $category = factory(Category::class)->create();
        $subcategory = factory(Category::class)->make();

        $category->children()->save($subcategory);

        $response = $this->getJson('api/categories');

        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'slug' => $category->slug,
        ]);
    }

    /** @test */
    function it_returns_categories_with_correct_order()
    {
        $category = factory(Category::class)->create([
            'order' => 2,
        ]);

        $anotherCategory = factory(Category::class)->create([
            'order' => 1,
        ]);

        $response = $this->getJson('api/categories');

        $response->assertJsonCount(2, 'data');

        $response->assertSeeInOrder([
            $anotherCategory->slug,
            $category->slug,
        ]);
    }
}
