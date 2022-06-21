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
    }

    public function getCode(): string
    {
        $phpCode = $this->getHeader(self::INTERFACE_NAME);
        $phpCode .= $this->phpService->getInterfaceMethods();
        $phpCode .= $this->closeRootScope();

        return $phpCode;
    }
}