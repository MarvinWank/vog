<?php

namespace Vog\Test\Feature\Psr2;

use Psr2TestCase;
use Test\Interface1;
use Test\Interface2;
use Test\BaseClass;

class ValueObjectTest extends Psr2TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->runGeneration('valueObjects.json');
    }

    /**
     * @test
     */
    public function it_tests_values()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());

        self::assertEquals("Test Recipe", $recipe->getTitle());
        self::assertEquals(30, $recipe->getMinutesToPrepare());
        self::assertEquals(5.5, $recipe->getRating());
        self::assertTrue(DietStyle::OMNIVORE()->equals($recipe->getDietStyle()));
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
            "minutesToPrepare" => 30,
            "rating" => 5.5,
            "dietStyle" => DietStyle::VEGETARIAN()
        ]);

        self::assertEquals("Test Recipe", $recipe->getTitle());
        self::assertEquals(30, $recipe->getMinutesToPrepare());
        self::assertEquals(5.5, $recipe->getRating());
        self::assertTrue(DietStyle::VEGETARIAN()->equals($recipe->getDietStyle()));
    }

    /**
     * @test
     */
    public function it_tests_to_array()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        $recipe = $recipe->toArray();

        self::assertEquals("Test Recipe", $recipe['title']);
        self::assertEquals(30, $recipe['minutesToPrepare']);
        self::assertEquals(5.5, $recipe['rating']);
        self::assertEquals("VEGAN", $recipe['dietStyle']);
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
        self::assertEquals("VEGAN", $recipe_as_array['recipe1']['dietStyle']);

        self::assertEquals("Test Recipe 2", $recipe_as_array['recipe2']['title']);
        self::assertEquals("VEGETARIAN", $recipe_as_array['recipe2']['dietStyle']);
    }

    /**
     * @param $value
     * @test
     * @dataProvider undefined_datatype_data_provider
     */
    public function it_tests_value_object_with_undefined_datatype($value)
    {
        $object = new ValueObjectNoDataType($value);
        self::assertEquals($object->getProperty(), $value);
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
        $instance = new ImplementsOne("bar", 42);
        self::assertInstanceOf(Interface1::class, $instance);
    }

    /**
     * @test
     */
    public function it_tests_implementation_generation_with_multiple_interfaces()
    {
        $instance = new ImplementsMany("bar", 42);
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
        self::assertEquals("bar", $instance->getFoo());
        $instance->setFoo("foobar");
        self::assertEquals("foobar", $instance->getFoo());
    }

    /**
     * @test
     */
    public function it_tests_explicit_immutability()
    {
        $instance = new ExplicitlyImmutableObject("bar");
        self::assertEquals("bar", $instance->getFoo());

        self::assertFalse(method_exists($instance, "setFoo"));
    }

    /**
     * @test
     */
    public function it_tests_implicit_immutability()
    {
        $instance = new ImplicitlyImmutableObject("bar");
        self::assertEquals("bar", $instance->getFoo());

        self::assertFalse(method_exists($instance, "setFoo"));
    }

    /**
     * @test
     */
    public function it_tests_with_functions()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());

        self::assertEquals("Test Recipe", $recipe->getTitle());
        self::assertEquals(30, $recipe->getMinutesToPrepare());
        self::assertEquals(5.5, $recipe->getRating());
        self::assertTrue(DietStyle::OMNIVORE()->equals($recipe->getDietStyle()));
        self::assertEquals("Test Recipe", strval($recipe));
        self::assertEquals("Test Recipe", $recipe->toString());

        $recipe = $recipe->withTitle("New Title");
        self::assertEquals("New Title", $recipe->getTitle());
        $recipe = $recipe->withMinutesToPrepare(31);
        self::assertEquals(31, $recipe->getMinutesToPrepare());
        $recipe = $recipe->withRating(10);
        self::assertEquals(10, $recipe->getRating());
        $recipe = $recipe->withDietStyle(DietStyle::VEGAN());
        self::assertEquals(DietStyle::VEGAN(), $recipe->getDietStyle());
    }

    /**
     * @test
     */
    public function it_tests_camelcase()
    {
        $value = "lorem ipsum";
        $object = new WithCamelCase($value);

        self::assertEquals($value, $object->getCamelCased());
        $array = $object->toArray();
        self::assertEquals(['camelCased' => $value], $array);

        $new = $object::fromArray($array);
        self::assertEquals($object, $new);
    }

    /**
     * @test
     */
    public function it_tests_with_underscore()
    {
        $value = "lorem ipsum";
        $object = new WithUnderscore($value);
        self::assertTrue(method_exists($object, "getNoCamelCase"));
        self::assertTrue(method_exists($object, "withNoCamelCase"));

        self::assertFalse(method_exists($object, "getNo_camel_case"));

        $array = $object->toArray();
        self::assertEquals(['noCamelCase' => $value], $array);
    }

    /**
     * @test
     */
    public function it_tests_dateTime()
    {
        $recipe = new RecipeWithDate("Test Recipe", 30, 5.5, DateTimeImmutable::createFromFormat(
            "Y-m-d",
            "2000-07-18"
        ));

        $array = $recipe->toArray();

        $this->assertEquals("2000-07-18", $array['creationDate']);
    }

    /**
     * @test
     */
    public function it_tests_dateTime_with_individual_format()
    {
        $recipe = new RecipeWithIndividualDateFormat("Test Recipe", 30, 5.5, DateTimeImmutable::createFromFormat(
            "Y-m-d",
            "2000-07-18"
        ));

        $array = $recipe->toArray();

        $this->assertEquals("18.07.2000", $array['creationDate']);
    }

    /**
     * @test
     */
    public function it_tests_dateTimeFromArray()
    {
        $recipe = RecipeWithIndividualDateFormat::fromArray([
            "title" => "Test Recipe",
            "minutesToPrepare" => 30,
            "rating" => 5.5,
            "creationDate" => DateTimeImmutable::createFromFormat(
                "Y-m-d",
                "2000-07-18"
            )
        ]);

        $this->assertEquals("2000-07-18",$recipe->getCreationDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function it_tests_dateTimeFromArray_with_string_value()
    {
        $recipe = RecipeWithIndividualDateFormat::fromArray([
            "title" => "Test Recipe",
            "minutesToPrepare" => 30,
            "rating" => 5.5,
            "creationDate" => "18.07.2000"
        ]);

        $this->assertEquals("2000-07-18",$recipe->getCreationDate()->format('Y-m-d'));
    }

    /** @test */
    public function it_tests_from_array_with_unset_properties_test()
    {
        $recipe = RecipeWithNullableProperties::fromArray([
            "title" => "Test Recipe",
            "rating" => 5.5,
        ]);

        $this->assertNull($recipe->getDietStyle());

        $recipeAsArray = $recipe->toArray();
        $this->assertNull($recipeAsArray['dietStyle']);
    }
}