<?php

namespace Vog\Generator\Php\Enum;

use Vog\Generator\Php\AbstractPhpGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

class PhpEnumGenerator extends AbstractPhpGenerator
{
    private ?string $enumDataType;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNamespace, $rootDir);

        $this->enumDataType = $definition->enumDataType();
    }

    public function getCode(): string
    {
        $phpcode = $this->phpService->generatePhpHeader(
            $this->name,
            $this->getNamespace(),
            [],
            false,
            null,
            [],
            'enum',
            $this->enumDataType
        );

        foreach ($this->getValues() as $case => $value){
            $phpcode .= "\n";
            if (is_numeric($case)){
                $case = $value;
            }

            $phpcode .= "    case $case";
            if ($case !== $value){
                $phpcode .= " = '$value'";
            }
            $phpcode .= ";";
        }
        $phpcode .= $this->closeRootScope();

        return $phpcode;
    }

}