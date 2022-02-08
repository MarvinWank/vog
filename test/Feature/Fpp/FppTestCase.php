<?php

namespace Feature\Fpp;

use PHPUnit\Framework\TestCase;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;

class FppTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate",
            __DIR__ . "/testObjectsFpp/value.json"
        ];
        $hub = new CommandFactory();

        $config = [
            'generatorOptions' => [
                'target' => TargetMode::MODE_FPP()->name(),
                'dateTimeFormat' => "Y-m-d",
                'toArrayMode' => ToArrayMode::DEEP()
            ],
        ];
        $hub->buildCommand($argv, $config);
    }
}