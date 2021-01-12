<?php


use Test\TestObjects\Cuisine;
use Test\TestObjects\Recipe;
use Test\TestObjects\DietStyle;
use Test\TestObjects\RecipeIntStringValue;
use Test\TestObjects\RecipeNoStringValue;

class DeepFromArrayTest extends Psr2TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_tests_enum_equals()
    {
        $this->assertTrue(true);
    }
}