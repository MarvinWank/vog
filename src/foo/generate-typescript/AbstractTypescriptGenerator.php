<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\Commands\Generate\AbstractGenerator;
use Vog\ValueObjects\Config;

abstract class AbstractTypescriptGenerator extends AbstractGenerator
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

    abstract public function getTypescriptCode(): string;

    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    public function getTargetFilepath(): string
    {
        return $this->target_filepath . DIRECTORY_SEPARATOR . ucfirst($this->name) . ".ts";
    }

    protected function sanitizeDataTypeForTypescript(string $dataType): string
    {
        $dataType = str_replace("?", "", $dataType);
        $dataType = str_replace("\\", "", $dataType);

        if ($dataType === "DateTime" || $dataType === "DateTimeImmutable"){
            $dataType = "string";
        }

        if ($this->isPrimitivePhpType($dataType)){
            $dataType = self::PRIMITIVE_TYPES_MAP[$dataType];
        }

        return $dataType;
    }
}