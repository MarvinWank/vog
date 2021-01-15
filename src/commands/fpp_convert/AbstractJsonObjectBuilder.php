<?php


namespace Vog\FppConvert;


abstract class AbstractJsonObjectBuilder
{
    protected ObjectType $type;
    protected string $name;
    protected array $values;

    protected ?string $extends;
    protected array $implements;
    protected bool $is_final;
    protected bool $is_mutable;

    public function __construct(string $name, ObjectType $objectType, array $values)
    {
        $this->name = $name;
        $this->type = $objectType;
        $this->values = $values;
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