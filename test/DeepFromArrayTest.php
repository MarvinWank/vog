<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\RecipeSet;
use Test\TestObjects\Enum;
use Test\TestObjects\Recipe;
use Test\TestObjects\DietStyle;
use Test\TestObjects\ValueObjectWithNestedSet;

class DeepFromArrayTest extends Psr2TestCase
{
    /**
     * @test
     */
    public function it_tests_create_enum_from_array()
    {
        $result = is_a(DietStyle::class, Enum::class, true);
        self::assertTrue($result);

        $ref = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());
        $arr = $ref->toArray();

        $val = Recipe::fromArray($arr);
        self::assertTrue($ref->equals($val));
    }

    /**
     * @test
     */
    public function it_tests_object_with_nested_set() {
        $ref = new ValueObjectWithNestedSet("Recipe List", RecipeSet::fromArray([
            Recipe::fromArray([
                "title" => "Test Recipe1",
                "minutesToPrepare" => 30,
                "rating" => 5.5,
                "dietStyle" => DietStyle::VEGETARIAN()
            ]),
            Recipe::fromArray([
                "title" => "Test Recipe2",
                "minutesToPrepare" => 25,
                "rating" => 5.5,
                "dietStyle" => DietStyle::VEGAN()
            ])
        ]));

        $arr = $ref->toArray();
        $val = ValueObjectWithNestedSet::fromArray($arr);
        self::assertTrue($ref->equals($val));

        self::assertEquals($ref->toArray(), $val->toArray());
    }
}