<?php

use Feature\FeatureTestCase;
use Vog\Factories\CommandFactory;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;


class Psr2TestCase extends FeatureTestCase
{
    protected function runGeneration(string $valueFile)
    {
        $argv = [
            __DIR__ . "/testObjects/" . $valueFile,
        ];
        $hub = new CommandFactory();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_PSR2()->name(),
                'dateTimeFormat' => "Y-m-d",
                'toArrayMode' => ToArrayMode::DEEP()
            ],
        ];
        $command = $hub->buildCommand('generate', $argv, Config::fromArray($config));
        $command->run();
    }
}