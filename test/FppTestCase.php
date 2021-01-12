<?php

use PHPUnit\Framework\TestCase;
use Vog\CommandHub;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\GeneratorOptions;

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
                'target' => TargetMode::MODE_FPP()->value()
            ],
        ];
        $hub->run($argv, $config);
    }
}