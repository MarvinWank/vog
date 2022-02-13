<?php

namespace Vog\Commands\Generate;

use Vog\ValueObjects\Config;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

abstract class AbstractGenerator
{
    protected GeneratorOptions $generatorOptions;
    protected VogDefinition $definition;

    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';
    protected const BAD_METHOD_CALL_EXCEPTION = 'BadMethodCallException';
    protected const USE_EXCEPTIONS = [self::UNEXPECTED_VALUE_EXCEPTION, self::INVALID_ARGUMENT_EXCEPTION];
    protected const PHP_PRIMITIVE_TYPES = ["", "string", "?string", "int", "?int", "float", "?float", "bool", "?bool", "array", "?array"];

    abstract public function setValues(array $values): void;

    abstract public function getTargetFilepath(): string;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions)
    {
        $this->definition = $definition;
        $this->generatorOptions = $generatorOptions;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTargetFilepath(string $targetFilepath): void
    {
        $this->targetFilepath = $targetFilepath;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    protected function isPrimitivePhpType(string $type): bool
    {
        return in_array($type, self::PHP_PRIMITIVE_TYPES);
    }

}