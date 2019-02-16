<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    function it_users_the_slug_for_the_route_key_name()
    {
        $product = new Product();

        $this->assertEquals('slug', $product->getRouteKeyName());
    }

    /** @test */
    function it_has_many_categories()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            $category = factory(Category::class)->make()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
        $this->assertTrue($product->categories->first()->is($category));
    }
}
