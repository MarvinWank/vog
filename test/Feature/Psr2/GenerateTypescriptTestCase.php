<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../autoload.php";

use PHPUnit\Framework\TestCase;
use Vog\CommandFactory;
use Vog\ValueObjects\TargetMode;


class GenerateTypescriptTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate-typescript",
            __DIR__ . "/testObjects/value.json",
            __DIR__ . "/testObjectsTypescript/types.d.ts"
        ];
        $hub = new CommandFactory();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_PSR2()->name(),
                'dateTimeFormat' => "Y-m-d",
                'toArrayMode' => 'DEEP'
            ],
        ];
        $hub->buildCommand($argv, $config);
    }

    /**
     * @test
     */
    public function it_tests_typescript_generation()
    {
        $referenceFile = file_get_contents(__DIR__ . "/typescriptReferenceFile.ts");
        $generatedFile = file_get_contents(__DIR__ . "/testObjectsTypescript/types.d.ts");

        $this->assertEquals($referenceFile, $generatedFile);
    }
}