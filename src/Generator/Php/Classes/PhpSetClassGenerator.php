<?php

namespace Vog\Generator\Php\Classes;

use Vog\Service\Php\SetService;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

//TODO: Support for Union Types
final class PhpSetClassGenerator extends AbstractPhpClassGenerator
{
    protected array $implements = ['Set', '\Countable', '\ArrayAccess', '\Iterator'];
    private SetService $setService;
    private string $itemType;


    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNamespace, $rootDir);

        $this->setService = new SetService();

        $this->itemType = $definition->itemType() ?? '';
    }

    public function getCode(): string
    {
        $phpcode = $this->phpService->generatePhpClassHeader(
            $this->name,
            $this->getNamespace(),
            [],
            $this->isFinal,
            $this->extends,
            $this->implements
        );
        $phpcode .= $this->generateConstructor();
        $phpcode .= $this->generateFromArray($this->name, $this->itemType);
        $phpcode .= $this->generateToArray($this->itemType);
        $phpcode .= $this->generateGenericFunctions($this->itemType);
        $phpcode .= $this->generateMutability($this->isMutable, $this->itemType);
        $phpcode .= $this->closeRootScope();

        return $phpcode;
    }

    protected function generateConstructor(): string
    {
        return $this->phpService->generateConstructor([]);
    }

    //TODO: toggle between method_exists() Check and Interface Check according to configuration --> Generator
    protected function generateToArray(string $itemType): string
    {
        if ($this->isPrimitiveType($itemType)) {
            return $this->setService->generateToArrayPrimitive();
        }
        return $this->setService->generateToArrayNonPrimitive();
    }

    protected function generateFromArray(string $name, string $itemType): string
    {
        if (empty($itemType)) {
            return $this->setService->generateFromArrayForUnspecifiedType();
        } else if ($this->isPrimitiveType($itemType)) {
            return $this->setService->generateFromArrayForPrimitiveType($itemType, $name);
        }
        return $this->setService->generateFromArrayForNonPrimitiveType($itemType, $name);
    }

    protected function generateGenericFunctions(string $itemType): string
    {
        return $this->setService->generateGenericFunctions($itemType);
    }

    protected function generateMutability(bool $isMutable, string $itemType): string
    {
        $phpcode = "";

        if (!$isMutable) {
            $phpcode .= $this->setService->generateAddFunction($itemType);

            //TODO: Rethink $item->toArray() approach without further check
            if (!$this->isPrimitiveType($this->itemType)) {
                $phpcode .= $this->setService->generateRemoveForNonPrimitiveType();
            } else {
                $phpcode .= $this->setService->generateRemoveForPrimitiveType();
            }
        } else {
            $phpcode .= $this->setService->generateMutableAddFunction($itemType);
            if (!$this->isPrimitiveType($this->itemType)) {
                $phpcode .= $this->setService->generateMutableRemoveForNonPrimitiveType();
            }
            else {
                $phpcode .= $this->setService->generateMutableRemoveForPrimitiveType();
            }
        }

        return $phpcode;
    }
}