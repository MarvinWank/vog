<?php

namespace Vog\Commands\Generate;

use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

abstract class AbstractGenerator
{
    protected GeneratorOptions $generatorOptions;

    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';

    protected string $name;
    protected string $rootDirectory;
    protected string $subDirectory;
    protected ?array $values;
    protected VogTypes $type;

    abstract public function getAbsoluteFilepath(): string;

    abstract public function getCode(): string;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootDirectory)
    {
        $this->generatorOptions = $generatorOptions;

        $this->name = $definition->name();
        $this->rootDirectory = $rootDirectory;
        $this->subDirectory = $definition->directory() === "./" ? '' : $definition->directory();
        $this->type = $definition->type();
        $this->values = $definition->values();
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
        return $this->values;
    }


}