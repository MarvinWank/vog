<?php

use Feature\FeatureTestCase;
use Vog\CommandFactory;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;


class Psr2TestCase extends FeatureTestCase
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
                'toArrayMode' => ToArrayMode::DEEP()
            ],
        ];
        $hub->buildCommand($argv, $config);
    }
}