<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\ValueObjects\Config;

class ValueObjectToTypescriptBuilder extends AbstractTypescriptBuilder
{
    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
    }

    public function getTypescriptCode(): string
    {
        $typescriptCode = <<<EOT
interface $this->name {

EOT;

        foreach ($this->values as $name => $dataType){
            if (strpos($dataType, "?") === false){
                $typescriptCode .= "\t$name: ";
            }else{

                $typescriptCode .= "\t$name?: ";
            }

            $dataType = $this->sanitizeDataTypeForTypescript($dataType);

            $typescriptCode .= "$dataType";
            $typescriptCode .= "\n";
        }
        $typescriptCode .= "}\n\n";

        return $typescriptCode;
    }


}