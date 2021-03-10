<?php

namespace Vog\Commands\Generate;

use Vog\ValueObjects\Config;
use Vog\ValueObjects\TargetMode;

abstract class AbstractBuilder
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

    abstract public function setValues(array $values): void;

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

    public function getTargetFilepath(): string
    {
        return $this->target_filepath . DIRECTORY_SEPARATOR . ucfirst($this->name) . ".php";
    }

    public function getValues(): array
    {
        return $this->values;
    }

    protected function closeClass($phpcode): string
    {
        $phpcode .= <<<EOT

}
EOT;
        return $phpcode;
    }
}