<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\ValueObjects\Config;

class ValueObjectToTypescriptBuilder extends AbstractTypescriptGenerator
{
    protected ?string $extends;

    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
        $this->extends = null;
    }

    public function setExtends(string $extends)
    {
        $extends = $this->sanitizeDataTypeForTypescript($extends);
        $this->extends = $extends;
    }

    public function getTypescriptCode(): string
    {
        $typescriptCode = <<<EOT
export interface $this->name
EOT;

        if ($this->extends !== null){
            $typescriptCode .= " extends $this->extends";
        }

        $typescriptCode .= " {\n";

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