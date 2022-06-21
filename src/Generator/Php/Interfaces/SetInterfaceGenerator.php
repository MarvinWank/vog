<?php

namespace Vog\Generator\Php\Interfaces;

use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

class SetInterfaceGenerator extends AbstractPhpInterfaceGenerator
{
    private const INTERFACE_NAME = 'Set';
    private const IMPLEMENTS = [
        'Countable',
        'ArrayAccess',
        'Iterator'
    ];

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNameSpace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNameSpace, $rootDir);

        $this->name = self::INTERFACE_NAME;
    }

    public function getCode(): string
    {
        $phpCode = $this->getHeader($this->name, self::IMPLEMENTS);
        $phpCode .= $this->phpService->getInterfaceMethods();
        $phpCode .= $this->closeRootScope();

        return $phpCode;
    }
}