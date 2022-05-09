<?php
namespace Vog\Test;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;

class VogTestCase extends TestCase
{
    protected function getDummyConfiguration(): Config
    {
        $generatorOptions = new GeneratorOptions(
            TargetMode::MODE_PSR2(),
            null,
            ToArrayMode::DEEP()
        );
        return new Config($generatorOptions);
    }
}