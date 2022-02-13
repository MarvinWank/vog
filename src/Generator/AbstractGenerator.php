<?php

namespace Vog\Commands\Generate;

use Vog\ValueObjects\Config;
use Vog\ValueObjects\TargetMode;

abstract class AbstractGenerator
{
    protected string $type;
    protected string $name;
    protected Config $config;
    protected string $namespace;
    protected string $target_filepath;
    protected array $values;

    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';
    protected const BAD_METHOD_CALL_EXCEPTION = 'BadMethodCallException';
    protected const USE_EXCEPTIONS = [self::UNEXPECTED_VALUE_EXCEPTION, self::INVALID_ARGUMENT_EXCEPTION];
    protected const PHP_PRIMITIVE_TYPES = ["", "string", "?string", "int", "?int", "float", "?float", "bool", "?bool", "array", "?array"];

    abstract public function setValues(array $values): void;
    abstract  public function getTargetFilepath(): string;

    public function __construct(string $name, Config $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTargetFilepath(string $target_filepath): void
    {
        $this->target_filepath = $target_filepath;
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