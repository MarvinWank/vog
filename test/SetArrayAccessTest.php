<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeSet;
use Test\TestObjects\DietStyle;
use BadMethodCallException;

class SetArrayAccessTest extends Psr2TestCase
{
    /**
     * @test
     */
    public function it_test_arrayaccess()
    {
        $data = [
            [
                'title' => 'Omni',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::OMNIVORE()
            ],
            [
                'title' => 'Vegan',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGAN()
            ],
            [
                'title' => 'Vegetarian',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGETARIAN()
            ]
        ];

        $set = RecipeSet::fromArray($data);

        $recipe = $set[0];
        self::assertInstanceOf(Recipe::class, $recipe);

        $looping = false;
        foreach ($set as $key => $recipe) {
            $looping = true;
            self::assertIsInt($key);
            self::assertInstanceOf(Recipe::class, $recipe);
        }

        self::assertTrue($looping);
        self::assertEquals(3, count($set));
    }

    /**
     * @test
     */
    public function it_test_exception_setoffset()
    {
        self::expectException(BadMethodCallException::class);
        $data = [
            [
                'title' => 'Omni',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::OMNIVORE()
            ],
            [
                'title' => 'Vegan',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGAN()
            ],
            [
                'title' => 'Vegetarian',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGETARIAN()
            ]
        ];

        $set = RecipeSet::fromArray($data);
        $set[0] = Recipe::fromArray([
            'title' => 'Vegetarian',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::VEGETARIAN()
        ]);
    }

    /**
     * @test
     */
    public function it_test_exception_unsetoffset()
    {
        self::expectException(BadMethodCallException::class);
        $data = [
            [
                'title' => 'Omni',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::OMNIVORE()
            ],
            [
                'title' => 'Vegan',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGAN()
            ],
            [
                'title' => 'Vegetarian',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGETARIAN()
            ]
        ];

        $set = RecipeSet::fromArray($data);
        unset($set[0]);
    }
}