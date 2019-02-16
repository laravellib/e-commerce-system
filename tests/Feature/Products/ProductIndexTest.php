<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    /** @test */
    function it_shows_a_collection_of_products()
    {
        $products = factory(Product::class, 2)->create();

        $response = $this->getJson('api/products');

        $response->assertJsonFragment([
            'id' => $products[0]->id,
            'name' => $products[0]->name,
            'slug' => $products[0]->slug,
        ]);

        $response->assertJsonFragment([
            'id' => $products[0]->id,
            'name' => $products[1]->name,
            'slug' => $products[1]->slug,
        ]);
    }

    /** @test */
    function it_has_paginated_data()
    {
        $this->getJson('api/products')->assertJsonStructure([
            'links', 'data', 'meta'
        ]);
    }
}
