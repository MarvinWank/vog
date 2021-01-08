<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

use PHPUnit\Framework\TestCase;
use Vog\CommandHub;
use Vog\ConfigOptions;
use Vog\Generate;

class Psr2TestCase extends TestCase
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
                'target' => ConfigOptions::MODE_PSR2,
                'phpVersion' => ConfigOptions::PHP_74,
                'quiet' => ConfigOptions::QUIET
            ],
        ];
        $hub->run($argv, $config);
    }
}