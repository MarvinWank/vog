<?php

namespace Vog\Service\Php;

class SetService extends AbstractPhpService
{
    protected const TEMPLATE_DIR = parent::TEMPLATE_DIR . 'Set/';

    //TODO: Make controller optionally public --> Generator
    public function generateConstructor(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'Constructor.vtpl');
    }


    public function generateToArrayNonPrimitive(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'ToArrayNonPrimitive.vtpl');
    }

    public function generateToArrayPrimitive(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'ToArrayPrimitive.vtpl');
    }

    public function generateFromArrayForUnspecifiedType(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'FromArrayForUnspecifiedType.vtpl');
    }

}