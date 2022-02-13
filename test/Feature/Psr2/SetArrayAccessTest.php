<?php

use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeSet;
use Test\TestObjects\DietStyle;
use Test\TestObjects\SetWithPrimitiveType;
use Test\TestObjects\MutableSetWithPrimitiveType;

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

    /**
     * @test
     */
    public function it_tests_set_with_primitive_type()
    {
        $data = ['foo', 'bar', 'foobar', '', 'fizz', 'buzz'];
        $set = SetWithPrimitiveType::fromArray($data);
        $result = $set->toArray();
        self::assertEquals($data, $result);

        $setWithFooRemoved = $set->remove('foo');
        self::assertEquals(['bar', 'foobar', '', 'fizz', 'buzz'], $setWithFooRemoved->toArray());

        $this->expectException(BadMethodCallException::class);
        $set[] = 'foo';
    }

    /**
     * @test
     */
    public function it_tests_mutable_set_with_primitive_type()
    {
        $data = ['foo', 'bar', 'foobar', '', 'fizz', 'buzz'];
        $set = MutableSetWithPrimitiveType::fromArray($data);
        $result = $set->toArray();
        self::assertEquals($data, $result);

        // attach 'foo' to the end
        $set[] = 'foo';
        $data[] = 'foo';

        $this->assertEquals($data, $set->toArray());

        // remove foo, will remove the "foo" on index:0
        $set->remove('foo');

        array_shift($data);
        $this->assertTrue($set->equals(MutableSetWithPrimitiveType::fromArray($data)));

        // remove foo (the one on the end)
        $set->remove('foo');
        array_pop($data);
        $this->assertEquals($data, $set->toArray());
    }
}