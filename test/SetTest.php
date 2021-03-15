<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeSet;
use Test\TestObjects\DietStyle;
use Test\TestObjects\SetWithPrimitiveType;

class SetTest extends Psr2TestCase
{
    /**
     * @test
     */
    public function it_tests_set()
    {
        $data = [
            [
                'title' => 'Omni',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::OMNIVORE()->name()
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
        self::assertEquals(3, $set->count());

        $new = Recipe::fromArray([
            'title' => 'Omni2',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ]);

        $newSet = $set->add($new);
        self::assertEquals(4, $newSet->count());
        self::assertTrue($newSet->contains($new));
        self::assertEquals(3, $set->count());
        self::assertFalse($set->contains($new));

        $removedSet = $newSet->remove($new);
        self::assertEquals(3, $removedSet->count());
        self::assertFalse($removedSet->contains($new));
        self::assertEquals(4, $newSet->count());
        self::assertTrue($newSet->contains($new));
    }

    /**
     * @test
     */
    public function it_tests_to_array()
    {
        $data = [
            [
                'title' => 'Omni',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::OMNIVORE()->name()
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
        $result = $set->toArray();

        self::assertEquals("Omni", $result[0]['title']);
        self::assertEquals(20, $result[0]['minutesToPrepare']);
        self::assertEquals(5.0, $result[0]['rating']);
        self::assertEquals("OMNIVORE", $result[0]['dietStyle']);
    }

    /**
     * @test
     */
    public function it_tests_set_equals() {
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

        $set1 = RecipeSet::fromArray($data);
        $set2 = RecipeSet::fromArray($data);

        $result = $set1->equals($set2);
        self::assertIsBool($result);
        self::assertTrue($result);

        $newSet = $set2->remove(Recipe::fromArray([
            'title' => 'Vegetarian',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::VEGETARIAN()
        ]));

        $result = $set1->equals($newSet);
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function it_tests_set_with_primitive_type()
    {
        $data = ["foo", "bar", "foobar", "", "fizz", "buzz"];
        $set = SetWithPrimitiveType::fromArray($data);
        $result = $set->toArray();
        self::assertEquals($data, $result);

        $setWithFooRemoved = $set->remove('foo');
        self::assertEquals(["bar", "foobar", "", "fizz", "buzz"], $setWithFooRemoved->toArray());

        $setWithFooAddedAgain = $setWithFooRemoved->add("foo");
        self::assertEquals(["bar", "foobar", "", "fizz", "buzz", "foo"], $setWithFooAddedAgain->toArray());

    }
}