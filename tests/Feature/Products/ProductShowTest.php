<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    /** @test */
    function it_fails_if_a_product_cant_be_found()
    {
        $this->getJson('api/products/none')
            ->assertNotFound();
    }

    /** @test */
    function it_shows_a_product()
    {
        $product = factory(Product::class)->create();

        $response = $this->getJson("api/products/{$product->slug}");

        $response->assertJsonFragment([
            'id' => $product->id
        ]);
    }
}
