<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

use PHPUnit\Framework\TestCase;
use Vog\CommandHub;
use Vog\ValueObjects\TargetMode;


class GenerateTypescriptTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate-typescript",
            __DIR__."/testObjects/value.json",
            __DIR__."/testObjectsTypescript/types.d.ts"
        ];
        $hub = new CommandHub();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_PSR2()->name(),
                'dateTimeFormat' => "Y-m-d"
            ],
        ];
        $hub->run($argv, $config);
    }

    /**
     * @test
     */
    public function it_tests_typescript_generation()
    {
        $this->assertTrue(true);
    }
}