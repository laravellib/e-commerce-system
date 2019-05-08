<?php

namespace Tests\Unit\Models;

use App\Models\Collections\ProductVariationCollection;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use App\Money\Money;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    /** @test */
    function it_belongs_to_a_product_variation_type()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

    /** @test */
    function it_belongs_to_a_product()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }

    /** @test */
    function it_returns_money_instance_for_the_money()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Money::class, $variation->money);
    }

    /** @test */
    function it_returns_a_formatted_price()
    {
        $variation = factory(ProductVariation::class)->create([
            'price' => 1500,
        ]);

        $this->assertEquals('$15.00', $variation->price_formatted);
    }

    /** @test */
    function it_returns_the_product_price_if_price_is_missing()
    {
        $product = factory(Product::class)->create([
            'price' => 1500,
        ]);

        $variation = factory(ProductVariation::class)->create([
            'price' => null,
            'product_id' => $product->id,
        ]);

        $this->assertEquals($product->price, $variation->price);
        $this->assertTrue($product->money->equals($variation->money));
    }

    /** @test */
    function it_can_check_if_variation_price_is_different_to_the_product_price()
    {
        $product = factory(Product::class)->create([
            'price' => 1500,
        ]);

        $variation = factory(ProductVariation::class)->create([
            'price' => 2000,
            'product_id' => $product->id,
        ]);

        $this->assertTrue($variation->priceVaries());
    }

    /** @test */
    function it_has_many_stocks(): void
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            $stock = factory(Stock::class)->make()
        );

        $this->assertInstanceOf(Stock::class, $variation->stocks->first());
        $this->assertTrue($variation->stocks->first()->is($stock));
    }

    /** @test */
    function it_has_stock_information(): void
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertInstanceOf(ProductVariation::class, $variation->stock->first());
    }

    /** @test */
    function it_has_stock_count_pivot_within_stock_information(): void
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->saveMany(
            factory(Stock::class, 2)->make([
                'quantity' => 5
            ])
        );

        $this->assertEquals(10, $variation->stock->first()->pivot->stock);
        $this->assertEquals(10, $variation->stockCount());
    }

    /** @test */
    function it_has_in_stock_pivot_within_stock_information(): void
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5
            ])
        );

        $this->assertTrue((bool) $variation->stock->first()->pivot->in_stock);
        $this->assertTrue((bool) $variation->inStock());
    }

    /** @test */
    function it_can_get_the_minumum_stock_for_the_given_value(): void
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5
            ])
        );

        $this->assertEquals(5, $variation->minStock(200));
    }

    /** @test */
    function it_aggregates_into_collection()
    {
        $this->assertInstanceOf(ProductVariationCollection::class, ProductVariation::get());
    }
}
