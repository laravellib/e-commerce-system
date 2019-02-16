<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class ProductScopeTest extends TestCase
{
    /** @test */
    function it_can_scope_by_category()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            $category = factory(Category::class)->make()
        );

        $anotherProduct = factory(Product::class)->create();

        $response = $this->getJson("api/products?category={$category->slug}");

        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['slug' => $product->slug]);
        $response->assertJsonMissing(['slug' => $anotherProduct->slug]);
    }
}
