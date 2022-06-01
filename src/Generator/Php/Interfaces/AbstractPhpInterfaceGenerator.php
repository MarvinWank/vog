<?php

namespace Vog\Generator\Php\Interfaces;


use Vog\Generator\Php\AbstractPhpGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractPhpInterfaceGenerator extends AbstractPhpGenerator
{

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNameSpace)
    {
        parent::__construct($definition, $generatorOptions, $rootNameSpace);
    }

    abstract public function getCode(): string;

    protected function getHeader(): string
    {
        $phpcode = $this->phpService->generatePhpHeader(
            $this->name,
            $this->getNamespace(),
            [],
            false,
            null,
            $this->implements,
            'interface'
        );

        return $phpcode;
    }

}