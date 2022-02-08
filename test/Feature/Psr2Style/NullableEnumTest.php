<?php


use Test\TestObjects\Cuisine;

class NullableEnumTest extends Psr2TestCase
{
    /**
     * @test
     */
    public function it_tests_from_name_with_null()
    {
        $nullableEnum = Cuisine::fromName(null);

        self::assertNull($nullableEnum->name());
        self::assertNull($nullableEnum->value());
        self::assertTrue($nullableEnum->equals(null));
        self::assertNull($nullableEnum->toString());
        self::assertFalse($nullableEnum->equals(Cuisine::ASIATISCH()));
    }

    /**
     * @test
     */
    public function it_tests_from_value_with_null()
    {
        $nullableEnum = Cuisine::fromValue(null);

        self::assertNull($nullableEnum->name());
        self::assertNull($nullableEnum->value());
        self::assertTrue($nullableEnum->equals(null));
        self::assertNull($nullableEnum->toString());
        self::assertFalse($nullableEnum->equals(Cuisine::ASIATISCH()));
    }
}