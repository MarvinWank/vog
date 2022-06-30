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

    public function generateFromArrayForPrimitiveType(string $itemType, string $setName): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'FromArrayForPrimitive.vtpl', [
            'itemType' => $itemType,
            'setName' => $setName
        ]);
    }

    public function generateFromArrayForNonPrimitiveType(string $itemType, string $setName): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'FromArrayForNonPrimitiveType.vtpl', [
            'itemType' => $itemType,
            'setName' => $setName
        ]);
    }

    public function generateGenericFunctions(string $itemType): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'GenericFunctions.vtpl', [
           'itemType' => $itemType
        ]);
    }

    public function generateAddFunction(string $itemType): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'AddFunction.vtpl', [
            'itemType' => $itemType
        ]);
    }

    public function generateRemoveForNonPrimitiveType(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'RemoveFunction.vtpl', [
            'arraySearchFirstParam' => '$item->toArray()'
        ]);
    }

    public function generateRemoveForPrimitiveType(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'RemoveFunction.vtpl', [
            'arraySearchFirstParam' => '$item'
        ]);
    }

    public function generateMutableAddFunction(string $itemType): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'MutableAddFunction.vtpl', [
           'itemType' => $itemType
        ]);
    }

    public function generateMutableRemoveForNonPrimitiveType(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'MutableRemoveFunction.vtpl', [
            'arraySearchFirstParam' => '$item->toArray()'
        ]);
    }

    public function generateMutableRemoveForPrimitiveType(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'MutableRemoveFunction.vtpl', [
            'arraySearchFirstParam' => '$item'
        ]);
    }
}