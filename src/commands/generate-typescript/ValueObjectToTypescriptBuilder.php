<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\ValueObjects\Config;

class ValueObjectToTypescriptBuilder extends AbstractTypescriptBuilder
{
    // Primitive Type in PHP => Primitive Type in ts
    private const PRIMITIVE_TYPES_MAP = [
        "int" => "number",
        "float" => "number",
        "string" => "string",
        "array" => "[]",
        "" => "any",
        "bool" => "boolean"
    ];

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

            $dataType = $this->getCorrectDataType($dataType);

            $typescriptCode .= "$dataType";
            $typescriptCode .= "\n";
        }
        $typescriptCode .= "}\n\n";

        return $typescriptCode;
    }

    private function getCorrectDataType(string $dataType): string
    {
        $dataType = str_replace("?", "", $dataType);

        if ($this->isPrimitivePhpType($dataType)){
            $dataType = self::PRIMITIVE_TYPES_MAP[$dataType];
        }

        return $dataType;
    }
}