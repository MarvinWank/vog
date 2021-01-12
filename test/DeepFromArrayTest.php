<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\Cuisine;
use Test\TestObjects\Recipe;
use Test\TestObjects\DietStyle;
use Test\TestObjects\RecipeIntStringValue;
use Test\TestObjects\RecipeNoStringValue;

class DeepFromArrayTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_tests_create_enum_from_array()
    {
        $ref = new Recipe("Test Recipe", 30, 5.5, DietStyle::OMNIVORE());
        $arr = $ref->toArray();

        $val = Recipe::fromArray($arr);
        $this->assertTrue($ref->equals($val));
    }
}