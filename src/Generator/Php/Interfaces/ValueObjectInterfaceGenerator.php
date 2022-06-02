<?php

namespace Vog\Generator\Php\Interfaces;

use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

class ValueObjectInterfaceGenerator extends AbstractPhpInterfaceGenerator
{
    private const INTERFACE_NAME = 'ValueObject';

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNameSpace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNameSpace, $rootDir);

        $this->name = self::INTERFACE_NAME;
    }

    public function getCode(): string
    {
        $phpCode = $this->getHeader();
        $phpCode .= $this->phpService->getValueObjectInterfaceMethods();
        $phpCode .= $this->closeRootScope();

        return $phpCode;
    }
}