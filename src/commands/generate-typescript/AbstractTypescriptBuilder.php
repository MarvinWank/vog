<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\Commands\Generate\AbstractBuilder;
use Vog\ValueObjects\Config;

class AbstractTypescriptBuilder extends AbstractBuilder
{
    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }
}