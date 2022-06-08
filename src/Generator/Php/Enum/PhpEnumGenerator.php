<?php

namespace Vog\Generator\Php\Enum;

use Vog\Generator\Php\AbstractPhpGenerator;

class PhpEnumGenerator extends AbstractPhpGenerator
{
    public function getCode(): string
    {
        $phpcode = $this->phpService->generatePhpHeader(
            $this->name,
            $this->getNamespace(),
            [],
            false,
            null,
            [],
            'enum'
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