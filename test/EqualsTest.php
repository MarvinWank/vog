<?php


use Test\TestObjects\Cuisine;
use Test\TestObjects\Recipe;
use Test\TestObjects\DietStyle;
use Test\TestObjects\RecipeIntStringValue;
use Test\TestObjects\RecipeNoStringValue;

class EqualsTest extends Psr2TestCase
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
        $ref = DietStyle::fromValue(DietStyle::OMNIVORE);

        $val = DietStyle::OMNIVORE();
        $result = $ref->equals($val);

        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $val = DietStyle::VEGAN();
        $result  = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_tests_nullable_enum_equals() {
        $ref = Cuisine::fromValue(Cuisine::MEDITERAN);

        $val = Cuisine::MEDITERAN();
        $result = $ref->equals($val);

        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $val = Cuisine::ASIATISCH();
        $result  = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function is_tests_valueobject_equals() {
        $data = [
            'title' => 'Example',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ];

        $ref = new Recipe(...array_values($data));
        $val = new Recipe(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $data['dietStyle'] = DietStyle::VEGAN();
        $val = new Recipe(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function is_tests_valueobject_intstring_equals() {
        $data = [
            'title' => 'Example',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ];

        $ref = new RecipeIntStringValue(...array_values($data));
        $val = new RecipeIntStringValue(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $data['dietStyle'] = DietStyle::VEGAN();
        $val = new RecipeIntStringValue(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function is_tests_valueobject_nostring_equals() {
        $data = [
            'title' => 'Example',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ];

        $ref = new RecipeNoStringValue(...array_values($data));
        $val = new RecipeNoStringValue(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $data['dietStyle'] = DietStyle::VEGAN();
        $val = new RecipeNoStringValue(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function is_tests_valueobject_nullvalues_equals() {
        $data = [
            'title' => 'Example',
            'minutesToPrepare' => 20,
            'rating' => 5,
            'dietStyle' => DietStyle::OMNIVORE()
        ];

        $ref = new Recipe(...array_values($data));
        $ref = $ref->withMinutesToPrepare(null);
        $val = $ref->withTitle($data['title']);

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $data['dietStyle'] = DietStyle::VEGAN();
        $val = new Recipe(...array_values($data));

        $result = $ref->equals($val);
        $this->assertTrue(is_bool($result));
        $this->assertFalse($result);
    }
}