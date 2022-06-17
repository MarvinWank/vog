<?php

namespace Vog\Generator\Php;

use Vog\Factories\GeneratorFactory;
use Vog\Generator\Php\Interfaces\AbstractPhpInterfaceGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractPhpVogDefinitionObjectGenerator extends AbstractPhpGenerator
{
    protected AbstractPhpInterfaceGenerator $interfaceGenerator;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNamespace, $rootDir);

        $interfaceGeneratorFactory = new GeneratorFactory();
        $this->interfaceGenerator = $interfaceGeneratorFactory->buildPhpInterfaceGenerator(
            $definition,
            $generatorOptions,
            $rootNamespace,
            $rootDir
        );
    }


    public function getInterfaceGenerator(): AbstractPhpInterfaceGenerator
    {
        return $this->interfaceGenerator;
    }
}