<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Money\Money;
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
}
