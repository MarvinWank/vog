<?php

namespace Vog\Generator\Php\Interfaces;


use Vog\Generator\Php\AbstractPhpGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractPhpInterfaceGenerator extends AbstractPhpGenerator
{

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNameSpace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNameSpace, $rootDir);
    }

    abstract public function getCode(): string;

    protected function getHeader(string $name, array $implements = []): string
    {
        return $this->phpService->generatePhpClassHeader(
            $name,
            $this->getNamespace(),
            [],
            false,
            null,
            $implements,
            'interface'
        );
    }

}