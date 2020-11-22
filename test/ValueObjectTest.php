<?php


use Test\TestObjects\DietStyle;
use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeIntStringValue;

class ValueObjectTest extends VogTestCase
{
    /**
     * @test
     */
    public function es_testet_values()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());

        $this->assertEquals("Test Recipe", $recipe->title());
        $this->assertEquals(30, $recipe->minutes_to_prepare());
        $this->assertEquals(5.5, $recipe->rating());
        $this->assertTrue(DietStyle::OMNIVORE()->equals($recipe->diet_style()));
        $this->assertEquals("Test Recipe", strval($recipe));
        $this->assertEquals("Test Recipe", $recipe->toString());
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
            "diet_style" => DietStyle::VEGETARIAN()
        ]);

        $this->assertEquals("Test Recipe", $recipe->title());
        $this->assertEquals(30, $recipe->minutes_to_prepare());
        $this->assertEquals(5.5, $recipe->rating());
        $this->assertTrue(DietStyle::VEGETARIAN()->equals($recipe->diet_style()));
    }

    /**
     * @test
     */
    public function es_testet_to_array()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        $recipe = $recipe->toArray();

        $this->assertEquals("Test Recipe", $recipe['title']);
        $this->assertEquals(30, $recipe['minutes_to_prepare']);
        $this->assertEquals(5.5, $recipe['rating']);
        $this->assertEquals("VEGAN", $recipe['diet_style']);
    }

    /**
     * @test
     */
    public function es_testet_mit_int_als_string_value()
    {
        $recipe = new RecipeIntStringValue("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        $this->assertEquals("5.5", strval($recipe));
    }

}