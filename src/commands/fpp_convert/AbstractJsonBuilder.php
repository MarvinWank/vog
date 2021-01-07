<?php


namespace Vog;


abstract class AbstractJsonBuilder
{
    protected string $type;
    protected string $name;
    protected string $namespace;
    protected string $target_filepath;
    protected array $values;

    protected ?string $extends;
    protected array $implements;
    protected bool $is_final;
    protected bool $is_mutable;

    public function __construct(string $name)
    {

    }

    abstract public function getJsonCode(): string;

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getTargetFilepath(): string
    {
        return $this->target_filepath;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getExtends(): ?string
    {
        return $this->extends;
    }

    public function getImplements(): array
    {
        return $this->implements;
    }

    public function isIsFinal(): bool
    {
        return $this->is_final;
    }

    public function isIsMutable(): bool
    {
        return $this->is_mutable;
    }



}