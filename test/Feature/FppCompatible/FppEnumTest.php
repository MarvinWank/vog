<?php


use PHPUnit\Framework\TestCase;
use Test\TestObjectsFpp\DietStyle;

class FppEnumTest extends FppTestCase
{
    /**
     * @test
     */
    public function es_testet_from_value()
    {
        $diet_style = DietStyle::fromName('OMNIVORE');

        self::assertEquals("Omnivore", $diet_style->value());
        self::assertEquals(DietStyle::OMNIVORE, $diet_style->value());
        self::assertEquals("OMNIVORE", $diet_style->name());
    }

    /**
     * @test
     */
    public function es_testet_from_name()
    {
        $diet_style = DietStyle::fromName("VEGAN");

        self::assertEquals("Vegan", $diet_style->value());
        self::assertEquals("VEGAN", $diet_style->name());
    }

    /**
     * @test
     */
    public function es_testet_from_function()
    {
        $diet_style = DietStyle::VEGETARIAN();

        self::assertEquals("Vegetarian", $diet_style->value());
        self::assertEquals("VEGETARIAN", $diet_style->name());
    }

    /**
     * @test
     */
    public function it_tests_equals()
    {
        $diet_style = DietStyle::OMNIVORE();
        $diet_style2 = DietStyle::OMNIVORE();

        self::assertTrue($diet_style->equals($diet_style2));
        self::assertTrue($diet_style2->equals($diet_style));
    }
}