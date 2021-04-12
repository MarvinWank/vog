<?php

use PHPUnit\Framework\TestCase;
use Test\TestObjects\DietStyle;
use Test\TestObjects\Recipe;
use Vog\CommandHub;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

class ShallowArrayGenerationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate",
            __DIR__."/testObjects/value.json"
        ];
        $hub = new CommandHub();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_PSR2()->name(),
                'dateTimeFormat' => "Y-m-d",
                'toArrayMode' => ToArrayMode::SHALLOW()
            ],
        ];
        $hub->run($argv, $config);
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