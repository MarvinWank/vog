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

class GeneratorFactory
{
    //TODO: Generator as singletons
    public function buildPhpGenerator(
        VogDefinition $definition,
        GeneratorOptions $generatorOptions,
        string $rootNamepath
    ): AbstractPhpGenerator
    {
        $fullFilepath = $definition->directory() . DIRECTORY_SEPARATOR . $definition->name() . '.php';
        switch ($definition->type()) {
            case VogTypes::enum():
                return new PhpEnumGenerator($definition, $generatorOptions, $rootNamepath);
            case VogTypes::nullableEnum():
                return new NullablePhpEnumGenerator($definition, $generatorOptions, $rootNamepath);
            case VogTypes::valueObject():
                return new PhpValueObjectGenerator($definition, $generatorOptions);
            case VogTypes::set():
                return new PhpSetGenerator($definition, $generatorOptions, $rootNamepath);
            default:
                throw new LogicException("Switch not exhaustive");
        }
    }
}