<?php

namespace Vog\Service\Php;

use Vog\ValueObjects\GeneratorOptions;

class SetService extends AbstractPhpService
{
    protected const TEMPLATE_DIR = parent::TEMPLATE_DIR . 'Set/';

    //TODO: Make controller optionally public --> Generator
    public function generateConstructor(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'Constructor.vtpl');
    }


    public function generateToArray(string $itemType): string
    {
        //TODO: toggle between method_exists() Check and Interface Check according to configuration --> Generator
        //TODO: This if condition is also the generators job
        if (!in_array($itemType, parent::PHP_PRIMITIVE_TYPES)) {
            return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'ToArrayNonPrimitive.vtpl');
        }
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'ToArrayPrimitive.vtpl');
    }

}