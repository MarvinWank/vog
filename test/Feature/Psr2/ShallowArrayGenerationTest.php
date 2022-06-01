<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\DietStyle;
use Test\TestObjects\Recipe;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;


class ShallowArrayGenerationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate",
            __DIR__ . "/testObjects/value.json"
        ];
        $hub = new CommandFactory();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_PSR2()->name(),
                'dateTimeFormat' => "Y-m-d",
                'toArrayMode' => ToArrayMode::SHALLOW()
            ],
        ];
        $hub->buildCommand($argv, $config);
    }

    /**
     * @test
     */
    public function it_tests_to_array()
    {
        $recipe = new Recipe("Test Recipe", 30, 5.5, DietStyle::VEGAN());
        $recipe = $recipe->toArray();

        self::assertEquals("Test Recipe", $recipe['title']);
        self::assertEquals(30, $recipe['minutesToPrepare']);
        self::assertEquals(5.5, $recipe['rating']);
        self::assertInstanceOf(DietStyle::class, $recipe['dietStyle']);
    }
}