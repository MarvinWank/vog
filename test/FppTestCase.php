<?php

use PHPUnit\Framework\TestCase;
use Vog\CommandHub;
use Vog\ConfigOptions;

class FppTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate",
            __DIR__."/testObjectsFpp/value.json"
        ];
        $hub = new CommandHub();

        $config = [
            'generatorOptions' => [
                'target' => ConfigOptions::MODE_FPP,
                'phpVersion' => ConfigOptions::PHP_74,
                'quiet' => ConfigOptions::QUIET
            ],
        ];
        $hub->run($argv, $config);
    }
}