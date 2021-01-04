<?php


use Test\TestObjects\BaseClass;
use Test\TestObjects\ChildOfNotFinal;
use Test\TestObjects\DesertRecipe;
use Test\TestObjects\DietStyle;
use Test\TestObjects\ExplicitlyImmutableObject;
use Test\TestObjects\implementsMany;
use Test\TestObjects\implementsOne;
use Test\TestObjects\ImplicitlyImmutableObject;
use Test\TestObjects\Interface1;
use Test\TestObjects\Interface2;
use Test\TestObjects\MutableObject;
use Test\TestObjects\NotFinal;
use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeCollection;
use Test\TestObjects\RecipeEnumStringValue;
use Test\TestObjects\RecipeIntStringValue;
use Test\TestObjects\ValueObjectNoDataType;
use Test\TestObjects\WithCamelCase;

class ValueObjectTest extends VogTestCase
{
    /**
     * @test
     */
    public function it_tests_values()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());

        self::assertEquals("Test Recipe", $recipe->title());
        self::assertEquals(30, $recipe->minutes_to_prepare());
        self::assertEquals(5.5, $recipe->rating());
        self::assertTrue(DietStyle::OMNIVORE()->equals($recipe->diet_style()));
        self::assertEquals("Test Recipe", strval($recipe));
        self::assertEquals("Test Recipe", $recipe->toString());
    }

    /**
     * @test
     */
    public function it_tests_from_array()
    {
        $recipe = Recipe::fromArray([
            "title" => "Test Recipe",
            "minutes_to_prepare" => 30,
            "rating" => 5.5,
            "diet_style" => DietStyle::VEGETARIAN()
        ]);

        self::assertEquals("Test Recipe", $recipe->title());
        self::assertEquals(30, $recipe->minutes_to_prepare());
        self::assertEquals(5.5, $recipe->rating());
        self::assertTrue(DietStyle::VEGETARIAN()->equals($recipe->diet_style()));
    }

    /**
     * @test
     */
    public function it_tests_to_array()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        $recipe = $recipe->toArray();

        self::assertEquals("Test Recipe", $recipe['title']);
        self::assertEquals(30, $recipe['minutes_to_prepare']);
        self::assertEquals(5.5, $recipe['rating']);
        self::assertEquals("VEGAN", $recipe['diet_style']);
    }

    /**
     * @test
     */
    public function it_tests_mit_int_als_string_value()
    {
        $recipe = new RecipeIntStringValue("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        self::assertEquals("5.5", strval($recipe));
    }

    /**
     * @test
     */
    public function it_tests_value_object_with_enum_as_string_value()
    {
        $recipe = new RecipeEnumStringValue("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        self::assertEquals('VEGAN', strval($recipe));
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
        self::assertEquals(5.5, strval($recipe));

        $recipe_as_array = $recipe->toArray();

        self::assertIsArray($recipe_as_array['recipe1']);
        self::assertIsArray($recipe_as_array['recipe2']);

        self::assertEquals("Test Recipe 1", $recipe_as_array['recipe1']['title']);
        self::assertEquals("VEGAN", $recipe_as_array['recipe1']['diet_style']);

        self::assertEquals("Test Recipe 2", $recipe_as_array['recipe2']['title']);
        self::assertEquals("VEGETARIAN", $recipe_as_array['recipe2']['diet_style']);
    }

    /**
     * @param $value
     * @test
     * @dataProvider undefined_datatype_data_provider
     */
    public function it_tests_value_object_with_undefined_datatype($value)
    {
        $object = new ValueObjectNoDataType($value);
        self::assertEquals($object->property(), $value);
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
        self::assertInstanceOf(BaseClass::class, $desert_recipe);
    }

    /**
     * @test
     */
    public function it_tests_implementation_generation_with_one_interface()
    {
        $instance = new implementsOne("bar", 42);
        self::assertInstanceOf(Interface1::class, $instance);
    }

    /**
     * @test
     */
    public function it_tests_implementation_generation_with_multiple_interfaces()
    {
        $instance = new implementsMany("bar", 42);
        self::assertInstanceOf(Interface1::class, $instance);
        self::assertInstanceOf(Interface2::class, $instance);
    }

    /**
     * @test
     */
    public function it_tests_class_is_nonfinal()
    {
        $instance = new ChildOfNotFinal("blaze it");
        self::assertInstanceOf(NotFinal::class, $instance);
    }

    /**
     * @test
     */
    public function it_tests_mutability()
    {
        $instance = new MutableObject("bar");
        self::assertEquals("bar", $instance->foo());
        $instance->set_foo("foobar");
        self::assertEquals("foobar", $instance->foo());
    }

    /**
     * @test
     */
    public function it_tests_explicit_immutability()
    {
        $instance = new ExplicitlyImmutableObject("bar");
        self::assertEquals("bar", $instance->foo());

        self::assertFalse(method_exists($instance, "set_foo"));
    }

    /**
     * @test
     */
    public function it_tests_implicit_immutability()
    {
        $instance = new ImplicitlyImmutableObject("bar");
        self::assertEquals("bar", $instance->foo());

        self::assertFalse(method_exists($instance, "set_foo"));
    }

    /**
     * @test
     */
    public function it_tests_with_functions()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());

        self::assertEquals("Test Recipe", $recipe->title());
        self::assertEquals(30, $recipe->minutes_to_prepare());
        self::assertEquals(5.5, $recipe->rating());
        self::assertTrue(DietStyle::OMNIVORE()->equals($recipe->diet_style()));
        self::assertEquals("Test Recipe", strval($recipe));
        self::assertEquals("Test Recipe", $recipe->toString());

        $recipe = $recipe->with_title("New Title");
        self::assertEquals("New Title", $recipe->title());
        $recipe = $recipe->with_minutes_to_prepare(31);
        self::assertEquals(31, $recipe->minutes_to_prepare());
        $recipe = $recipe->with_rating(10);
        self::assertEquals(10, $recipe->rating());
        $recipe = $recipe->with_diet_style(DietStyle::VEGAN());
        self::assertEquals(DietStyle::VEGAN(), $recipe->diet_style());
    }

    public function it_tests_camelcase()
    {
        $value = "lorem ipsum";
        $object = new WithCamelCase($value);

        self::assertEquals($value, $object->camelCased());
        self::assertEquals(['camelCased' => $value], $object->toArray());

        $new = $object->fromArray($object);
        self::assertTrue($object->equals($new));
    }
}