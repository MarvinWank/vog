<?php


use PHPUnit\Framework\TestCase;
use Test\TestObjects\DietStyle;

class EnumTest extends VogTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function es_testet_from_value()
    {
        $diet_style = DietStyle::fromName('ALLES');

        $this->assertEquals("alles", $diet_style->value());
        $this->assertEquals(DietStyle::ALLES, $diet_style->value());
        $this->assertEquals("ALLES", $diet_style->name());
    }

    /**
     * @test
     */
    public function es_testet_from_name()
    {
        $diet_style = DietStyle::fromName("VEGAN");

        $this->assertEquals("vegan", $diet_style->value());
        $this->assertEquals("VEGAN", $diet_style->name());
    }

    /**
     * @test
     */
    public function es_testet_from_function()
    {
        $diet_style = DietStyle::VEGETARISCH();

        $this->assertEquals("vegetarisch", $diet_style->value());
        $this->assertEquals("VEGETARISCH", $diet_style->name());
    }

    /**
     * @test
     */
    public function it_tests_equals()
    {
        $diet_style = DietStyle::ALLES();
        $diet_style2 = DietStyle::ALLES();

        $this->assertTrue($diet_style->equals($diet_style2));
        $this->assertTrue($diet_style2->equals($diet_style));
    }
}