<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\ValueObjects\Config;

class ValueObjectToTypescriptBuilder extends AbstractTypescriptBuilder
{
    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
    }



    public function getTypescriptCode(): string
    {

    }
}