<?php

namespace Vog\Commands\Generate;


use Vog\ValueObjects\Config;

class InterfaceBuilder extends AbstractBuilder
{
    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
        $this->type = "interface";
        $this->setIsFinal(false);
        $this->setIsMutable(false);
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader([], 'interface');
        $phpcode = $this->closeClass($phpcode);

        return $phpcode;
    }
}