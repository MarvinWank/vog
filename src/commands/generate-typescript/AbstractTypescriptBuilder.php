<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\Commands\Generate\AbstractBuilder;
use Vog\ValueObjects\Config;

abstract class AbstractTypescriptBuilder extends AbstractBuilder
{
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
}