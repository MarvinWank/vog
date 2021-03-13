<?php


namespace Vog\Commands\GenerateTypescript;


class EnumToTypescriptBuilder extends AbstractTypescriptBuilder
{
    public function getTypescriptCode(): string
    {
        $typeScriptCode = "export enum $this->name { \n";

        foreach ($this->values as $name => $value){

            $typeScriptCode .= "\t$name = '$value',\n";
        }

        $typeScriptCode .= "}\n\n";

        return $typeScriptCode;
    }

}