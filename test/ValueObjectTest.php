<?php


use Test\TestObjects\Recipe;

class ValueObjectTest extends VogTestCase
{
    /**
     * @test
     */
    public function es_testet_values()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, new DateTime());

        $this->assertEquals("Test Recipe", $recipe->title());
        $this->assertEquals(30, $recipe->minutes_to_prepare());
        $this->assertEquals(5.5, $recipe->rating());
        $this->assertInstanceOf(DateTime::class, $recipe->any_value());
    }

    /**
     * @test
     */
    public function es_testet_from_array()
    {
        $recipe = Recipe::fromArray([
            "title" => "Test Recipe",
            "minutes_to_prepare" => 30,
            "rating" => 5.5,
            "any_value" => "foo"
        ]);

        $this->assertEquals("Test Recipe", $recipe->title());
        $this->assertEquals(30, $recipe->minutes_to_prepare());
        $this->assertEquals(5.5, $recipe->rating());
        $this->assertEquals("foo", $recipe->any_value());
    }

    /**
     * @test
     */
    public function es_testet_to_array()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, 42);
        $recipe = $recipe->toArray();

        $this->assertEquals("Test Recipe", $recipe['title']);
        $this->assertEquals(30, $recipe['minutes_to_prepare']);
        $this->assertEquals(5.5, $recipe['rating']);
        $this->assertEquals(42, $recipe['any_value']);
    }
}