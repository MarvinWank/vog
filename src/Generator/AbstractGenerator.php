<?php

namespace Vog\Commands\Generate;

use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractGenerator
{
    protected GeneratorOptions $generatorOptions;
    protected VogDefinition $definition;

    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';
    protected const BAD_METHOD_CALL_EXCEPTION = 'BadMethodCallException';
    protected const PHP_PRIMITIVE_TYPES = ["", "string", "?string", "int", "?int", "float", "?float", "bool", "?bool", "array", "?array"];

    abstract public function setValues(array $values): void;

    abstract public function getAbsoluteFilepath(): string;

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

    protected function getValues(): ?array
    {
        return array_flip($this->definition->values());
    }

    protected function isPrimitivePhpType(string $type): bool
    {
        return in_array($type, self::PHP_PRIMITIVE_TYPES);
    }

}