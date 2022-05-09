<?php

namespace Integration;

use Vog\Test\VogTestCase;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;

class IntegrationTestCase extends VogTestCase
{
    protected function generateConfig(): Config
    {
        return new Config(new GeneratorOptions(
            TargetMode::MODE_PSR2(),
            null,
            ToArrayMode::DEEP()
        ));
    }
}