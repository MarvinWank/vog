<?php


use Test\TestObjects\BaseClass;
use Test\TestObjects\ChildOfNotFinal;
use Test\TestObjects\DesertRecipe;
use Test\TestObjects\DietStyle;
use Test\TestObjects\implementsMany;
use Test\TestObjects\implementsOne;
use Test\TestObjects\Interface1;
use Test\TestObjects\Interface2;
use Test\TestObjects\notFinal;
use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeCollection;
use Test\TestObjects\RecipeEnumStringValue;
use Test\TestObjects\RecipeIntStringValue;
use Test\TestObjects\ValueObjectNoDataType;

class ValueObjectTest extends GenerateTestCase
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

    /**
     * @test
     */
    public function it_tests_value_object_with_enum_as_string_value()
    {
        $recipe = new RecipeEnumStringValue("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        $this->assertEquals('VEGAN', strval($recipe));
    }

    /**
     * @test
     */
    public function it_tests_value_object_with_another_value_object_as_string_value()
    {
        $recipe = new RecipeCollection(
            new RecipeIntStringValue("Test Recipe 1", 30, 5.5, DietStyle::VEGAN()),
            new RecipeEnumStringValue("Test Recipe 2", 30, 5.5, DietStyle::VEGETARIAN())
        );

        //String value of recipe: $recipe_1; String value of $recipe_1: $rating
        $this->assertEquals(5.5, strval($recipe));

        $recipe_as_array = $recipe->toArray();

        $this->assertIsArray($recipe_as_array['recipe1']);
        $this->assertIsArray($recipe_as_array['recipe2']);

        $this->assertEquals("Test Recipe 1", $recipe_as_array['recipe1']['title']);
        $this->assertEquals("VEGAN", $recipe_as_array['recipe1']['diet_style']);

        $this->assertEquals("Test Recipe 2", $recipe_as_array['recipe2']['title']);
        $this->assertEquals("VEGETARIAN", $recipe_as_array['recipe2']['diet_style']);
    }

    /**
     * @param $value
     * @test
     * @dataProvider undefined_datatype_data_provider
     */
    public function it_tests_value_object_with_undefined_datatype($value)
    {
        $object = new ValueObjectNoDataType($value);
        $this->assertEquals($object->property(), $value);
    }

    public function undefined_datatype_data_provider()
    {
        return [
            "object" => [DietStyle::VEGETARIAN()],
            "string" => ["test"],
            "number" => [1234],
            "null" => [null]
        ];
    }

    /**
     * @test
     */
    public function it_tests_extension_generation()
    {
        $desert_recipe = new DesertRecipe(false, false);
        $this->assertInstanceOf(BaseClass::class, $desert_recipe);
    }

    /**
     * @test
     */
    public function it_tests_implementation_generation_with_one_interface()
    {
        $instance = new implementsOne("bar", 42);
        $this->assertInstanceOf(Interface1::class, $instance);
    }

    /**
     * @test
     */
    public function it_tests_implementation_generation_with_multiple_interfaces()
    {
        $instance = new implementsMany("bar", 42);
        $this->assertInstanceOf(Interface1::class, $instance);
        $this->assertInstanceOf(Interface2::class, $instance);
    }

    /**
     * @test
     */
    public function it_tests_class_is_nonfinal()
    {
        $instance = new ChildOfNotFinal("blaze it");
        $this->assertInstanceOf(NotFinal::class, $instance);
    }

}