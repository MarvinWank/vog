<?php


namespace Vog\Commands\GenerateTypescript;


class EnumToTypescriptBuilder extends AbstractTypescriptGenerator
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