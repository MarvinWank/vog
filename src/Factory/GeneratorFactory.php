<?php

namespace Vog\Factories;

use LogicException;
use Vog\Generator\Php\Classes\AbstractPhpClassGenerator;
use Vog\Generator\Php\Classes\NullablePhpEnumGenerator;
use Vog\Generator\Php\Classes\PhpEnumClassGenerator;
use Vog\Generator\Php\Classes\PhpSetClassGenerator;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\Generator\Php\Interfaces\AbstractPhpInterfaceGenerator;
use Vog\Generator\Php\Interfaces\EnumInterfaceGenerator;
use Vog\Generator\Php\Interfaces\SetInterfaceGenerator;
use Vog\Generator\Php\Interfaces\ValueObjectInterfaceGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class GeneratorFactory
{

    public function buildPhpGenerator(
        VogDefinition    $definition,
        GeneratorOptions $generatorOptions,
        string           $rootNamespace,
        string           $rootDir
    ): AbstractPhpClassGenerator
    {
        switch ($definition->type()) {
            case VogTypes::enum():
                return new PhpEnumClassGenerator($definition, $generatorOptions, $rootNamespace);
            case VogTypes::nullableEnum():
                return new NullablePhpEnumGenerator($definition, $generatorOptions, $rootNamespace);
            case VogTypes::valueObject():
                return new PhpValueObjectClassGenerator($definition, $generatorOptions, $rootNamespace, $rootDir);
            case VogTypes::set():
                return new PhpSetClassGenerator($definition, $generatorOptions, $rootNamespace);
            default:
                throw new LogicException("Switch not exhaustive");
        }
    }

    public function buildPhpInterfaceGenerator(
        VogDefinition    $definition,
        GeneratorOptions $generatorOptions,
        string           $rootNameSpace,
        string           $rootDir
    ): AbstractPhpInterfaceGenerator
    {
        switch ($definition->type()) {
            case VogTypes::nullableEnum():
            case VogTypes::enum():
                return new EnumInterfaceGenerator($definition, $generatorOptions, $rootNameSpace, $rootDir);
            case VogTypes::valueObject():
                return new ValueObjectInterfaceGenerator($definition, $generatorOptions, $rootNameSpace, $rootDir);
            case VogTypes::set():
                return new SetInterfaceGenerator($definition, $generatorOptions, $rootNameSpace, $rootDir);
            default:
                throw new LogicException("Switch not exhaustive");
        }
    }
}