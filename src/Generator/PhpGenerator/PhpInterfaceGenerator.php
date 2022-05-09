<?php

namespace Vog\Commands\Generate;


use Vog\ValueObjects\Config;

class PhpInterfaceGenerator extends AbstractPhpGenerator
{
    public function __construct(string $targetFilepath, Config $generatorOptions)
    {
        parent::__construct($targetFilepath, $generatorOptions);
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