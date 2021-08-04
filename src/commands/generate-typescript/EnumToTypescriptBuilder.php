<?php
declare(strict_types = 1);

namespace Vog\Commands\GenerateTypescript;


class EnumToTypescriptBuilder extends AbstractTypescriptBuilder
{
    public function getTypescriptCode(): string
    {
        $typeScriptCode = "export type $this->name =\n";

        foreach ($this->values as $name => $value){
            $typeScriptCode .= " | " . $name;
        }


        return $typeScriptCode;
    }

}