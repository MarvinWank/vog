<?php

namespace Vog\Factories;

use LogicException;
use Vog\Generator\Php\AbstractPhpGenerator;
use Vog\Generator\Php\AbstractPhpVogDefinitionObjectGenerator;
use Vog\Generator\Php\Classes\PhpSetClassGenerator;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\Generator\Php\Enum\NullablePhpLegacyEnumGenerator;
use Vog\Generator\Php\Enum\PhpEnumGenerator;
use Vog\Generator\Php\Enum\PhpLegacyEnumClassGenerator;
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
    ): AbstractPhpVogDefinitionObjectGenerator
    {
        switch ($definition->type()) {
            case VogTypes::enum():
            case VogTypes::nullableEnum():
                return $this->buildEnumGenerator(
                    $definition,
                    $generatorOptions,
                    $rootNamespace,
                    $rootDir,
                    phpversion()
                );
            case VogTypes::valueObject():
                return new PhpValueObjectClassGenerator($definition, $generatorOptions, $rootNamespace, $rootDir);
            case VogTypes::set():
                return new PhpSetClassGenerator($definition, $generatorOptions, $rootNamespace, $rootDir);
            default:
                throw new LogicException("Switch not exhaustive");
        }
    }

    private function buildEnumGenerator(
        VogDefinition    $definition,
        GeneratorOptions $generatorOptions,
        string           $rootNamespace,
        string           $rootDir,
        string           $phpVersion
    ): AbstractPhpVogDefinitionObjectGenerator
    {
        //TODO: manually toggle between standard/legacy enums
        if (version_compare($phpVersion, '8.1') >= 0){
            return new PhpEnumGenerator($definition, $generatorOptions, $rootNamespace, $rootDir);
        }
        if ($definition->type()->equals(VogTypes::enum())){
            return new PhpLegacyEnumClassGenerator($definition, $generatorOptions, $rootNamespace, $rootDir);
        }
        if ($definition->type()->equals(VogTypes::nullableEnum())){
            return new NullablePhpLegacyEnumGenerator($definition, $generatorOptions, $rootNamespace, $rootDir);
        }

        throw new LogicException("Clause not exhaustive");
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