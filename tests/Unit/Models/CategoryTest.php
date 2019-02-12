<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /** @test */
    function it_has_many_children()
    {
        $category = factory(Category::class)->create();

        $category->children()->saveMany([
            factory(Category::class)->make(),
            factory(Category::class)->make(),
        ]);

        $this->assertInstanceOf(Category::class, $category->children->first());
        $this->assertCount(2, $category->children);
    }

    /** @test */
    function it_can_fetch_only_parents()
    {
        $category = factory(Category::class)->create();

        $category->children()->save(
            factory(Category::class)->make()
        );

        $this->assertEquals(1, Category::parent()->count());
    }

    /** @test */
    function it_is_orderable_by_a_number_order()
    {
        $category = factory(Category::class)->create([
            'order' => 2,
        ]);

        $anotherCategory = factory(Category::class)->create([
            'order' => 1,
        ]);

        tap(Category::orderable()->get(), function ($categories) use ($category, $anotherCategory) {
            $this->assertTrue($anotherCategory->is($categories[0]));
            $this->assertTrue($category->is($categories[1]));
        });
    }
}
