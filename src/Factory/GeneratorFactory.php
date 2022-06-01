<?php

namespace Vog\Factories;

use LogicException;
use Vog\Generator\Php\Classes\AbstractPhpClassGenerator;
use Vog\Generator\Php\Classes\NullablePhpEnumGenerator;
use Vog\Generator\Php\Classes\PhpEnumClassGenerator;
use Vog\Generator\Php\Classes\PhpSetClassGenerator;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\Generator\Php\Interfaces\AbstractPhpInterfaceGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class GeneratorFactory
{

    public function buildPhpGenerator(
        VogDefinition    $definition,
        GeneratorOptions $generatorOptions,
        string           $rootNamepath
    ): AbstractPhpClassGenerator
    {
        $fullFilepath = $definition->directory() . DIRECTORY_SEPARATOR . $definition->name() . '.php';
        switch ($definition->type()) {
            case VogTypes::enum():
                return new PhpEnumClassGenerator($definition, $generatorOptions, $rootNamepath);
            case VogTypes::nullableEnum():
                return new NullablePhpEnumGenerator($definition, $generatorOptions, $rootNamepath);
            case VogTypes::valueObject():
                return new PhpValueObjectClassGenerator($definition, $generatorOptions, $rootNamepath);
            case VogTypes::set():
                return new PhpSetClassGenerator($definition, $generatorOptions, $rootNamepath);
            default:
                throw new LogicException("Switch not exhaustive");
        }
    }

    public function buildPhpInterfaceGenerator(
        VogDefinition $definition,
        GeneratorOptions $generatorOptions
    ): AbstractPhpInterfaceGenerator
    {
        switch ($definition->type()){
            case VogTypes::enum()
        }
    }
}