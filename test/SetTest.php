<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeSet;
use Test\TestObjects\DietStyle;

class SetTest extends Psr2TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

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
        $this->assertEquals(3, $set->count());

        $new = Recipe::fromArray([
            'title' => 'Omni2',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ]);

        $newSet = $set->add($new);
        $this->assertEquals(4, $newSet->count());
        $this->assertTrue($newSet->contains($new));
        $this->assertEquals(3, $set->count());
        $this->assertFalse($set->contains($new));

        $removedSet = $newSet->remove($new);
        $this->assertEquals(3, $removedSet->count());
        $this->assertFalse($removedSet->contains($new));
        $this->assertEquals(4, $newSet->count());
        $this->assertTrue($newSet->contains($new));
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
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $newSet = $set2->remove(Recipe::fromArray([
            'title' => 'Vegetarian',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::VEGETARIAN()
        ]));

        $result = $set1->equals($newSet);
        $this->assertFalse($result);
    }
}