<?php
namespace Vog\Test;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use ReflectionException;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;

class VogTestCase extends TestCase
{
    //TODO: Refactor/Remove
    protected function getDummyConfiguration(): Config
    {
        $generatorOptions = new GeneratorOptions(
            TargetMode::MODE_PSR2(),
            null,
            ToArrayMode::DEEP()
        );
        return new Config($generatorOptions);
    }

    protected function generateConfig(): Config
    {
        return new Config(new GeneratorOptions(
            TargetMode::MODE_PSR2(),
            null,
            ToArrayMode::DEEP()
        ));
    }

    /**
     * @throws ReflectionException
     * @return mixed
     */
    protected function callProtectedMethod($obj, $methodName, array $args) {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}