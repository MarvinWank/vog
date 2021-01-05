<?php


use Test\TestObjects\Recipe;
use Test\TestObjects\RecipeSet;
use Test\TestObjects\DietStyle;

class SetTest extends VogTestCase
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
            Recipe::fromArray([
                'title' => 'Omni',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::OMNIVORE()
            ]),
            Recipe::fromArray([
                'title' => 'Vegan',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGAN()
            ]),
            Recipe::fromArray([
                'title' => 'Vegetarian',
                'minutesToPrepare' => 20,
                'rating' => 5,
                'dietStyle' => DietStyle::VEGETARIAN()
            ])
        ];

        $set = RecipeSet::fromArray($data);
        $this->assertEquals(3, $set->count());

        $new = Recipe::fromArray([
            'title' => 'Omni',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ]);

        $set->add($new);
        $this->assertEquals(4, $set->count());
        $this->assertTrue($set->contains($new));

        $set->remove($new);
        $this->assertEquals(3, $set->count());
        $this->assertTrue($set->contains($new));

        $set->remove($new);
        $this->assertEquals(2, $set->count());
        $this->assertFalse($set->contains($new));
    }
}