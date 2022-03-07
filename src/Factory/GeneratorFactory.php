<?php

namespace Vog\Factories;

use LogicException;
use Vog\Commands\Generate\AbstractPhpGenerator;
use Vog\Commands\Generate\NullablePhpEnumGenerator;
use Vog\Commands\Generate\PhpEnumGenerator;
use Vog\Commands\Generate\PhpSetGenerator;
use Vog\Commands\Generate\PhpValueObjectGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;
use Vog\Vog;

class GeneratorFactory
{
    public function buildPhpGenerator(VogDefinition $definition, GeneratorOptions $generatorOptions): AbstractPhpGenerator
    {
        $fullFilepath = $definition->directory() . DIRECTORY_SEPARATOR . $definition->name() . '.php';
        switch ($definition->type()) {
            case VogTypes::enum():
                return new PhpEnumGenerator($definition, $generatorOptions);
            case VogTypes::nullableEnum():
                return new NullablePhpEnumGenerator($definition, $generatorOptions);
            case VogTypes::valueObject():
                return new PhpValueObjectGenerator($definition, $generatorOptions);
            case VogTypes::set():
                return new PhpSetGenerator($definition, $generatorOptions);
            default:
                throw new LogicException("Switch not exhaustive");
        }
    }
}