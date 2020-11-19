<?php


use Test\TestObjects\Cuisine;

class NullableEnumTest extends VogTestCase
{
    /**
     * @test
     */
    public function it_tests_from_name_with_null()
    {
        $nullableEnum = Cuisine::fromName(null);

        $this->assertNull($nullableEnum->name());
        $this->assertNull($nullableEnum->value());
        $this->assertTrue($nullableEnum->equals(null));
        $this->assertNull($nullableEnum->toString());
        $this->assertFalse($nullableEnum->equals(Cuisine::ASIATISCH()));
    }

    /**
     * @test
     */
    public function it_tests_from_value_with_null()
    {
        $nullableEnum = Cuisine::fromValue(null);

        $this->assertNull($nullableEnum->name());
        $this->assertNull($nullableEnum->value());
        $this->assertTrue($nullableEnum->equals(null));
        $this->assertNull($nullableEnum->toString());
        $this->assertFalse($nullableEnum->equals(Cuisine::ASIATISCH()));
    }
}