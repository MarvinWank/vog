<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

use PHPUnit\Framework\TestCase;
use Vog\CommandFactory;
use Vog\ValueObjects\TargetMode;


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
        $hub = new CommandFactory();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_PSR2()->name(),
                'dateTimeFormat' => "Y-m-d",
                'toArrayMode' => \Vog\ValueObjects\ToArrayMode::DEEP()
            ],
        ];
        $hub->buildCommand($argv, $config);
    }
}