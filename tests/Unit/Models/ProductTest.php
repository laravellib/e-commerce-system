<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use App\Money\Money;
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

    /** @test */
    function it_has_many_variations()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(ProductVariation::class)->make()
        );

        $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
        $this->assertTrue($product->variations->first()->is($variation));
    }

    /** @test */
    function it_returns_money_instance_for_the_money()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->money);
    }

    /** @test */
    function it_returns_a_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price' => 1500,
        ]);

        $this->assertEquals('$15.00', $product->price_formatted);
    }

    /** @test */
    function it_can_check_if_it_is_in_stock()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(ProductVariation::class)->make()
        );

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5
            ])
        );

        $this->assertTrue($product->inStock());
    }

    /** @test */
    function it_can_get_the_stock_count()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(ProductVariation::class)->make()
        );

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5
            ])
        );

        $this->assertEquals(5, $product->stockCount());
    }
}
