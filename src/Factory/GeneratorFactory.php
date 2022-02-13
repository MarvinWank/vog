<?php

namespace Vog\Factories;

use Vog\Commands\Generate\AbstractPhpGenerator;
use Vog\Commands\Generate\PhpEnumGenerator;
use Vog\Commands\Generate\NullablePhpEnumGenerator;
use Vog\Commands\Generate\PhpSetGenerator;
use Vog\Commands\Generate\PhpValueObjectGenerator;
use Vog\Exception\VogException;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class GeneratorFactory
{
    public function buildPhpGenerator(VogDefinition $definition, GeneratorOptions $generatorOptions): AbstractPhpGenerator
    {
        $fullFilepath = $definition->directory() . DIRECTORY_SEPARATOR . $definition->name() . '.php';
        switch ($definition->type()){
            case VogTypes::enum():
                return new PhpEnumGenerator()
        }
    }
}