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

        $this->itemType = $definition->itemType();
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
        if ($this->isPrimitiveType($itemType)){
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

    protected function generateMutability(){
    
        if (!$this->isMutable()) {
            $phpcode .= <<< EOT

    public function add($this->itemType \$item): self {
        \$values = \$this->toArray();
        \$values[] = \$item;
        return self::fromArray(\$values);
    }

    public function offsetSet(\$offset, \$value) {
        throw new BadMethodCallException('ArrayAccess offsetSet is forbidden, use ->add()');
    }

    public function offsetUnset(\$offset) {
        throw new BadMethodCallException('ArrayAccess offsetUnset is forbidden, use ->remove()');
    }
    
EOT;

            if (!$this->isPrimitiveType($this->itemType)){
                $phpcode .= <<< EOT

    public function remove($this->itemType \$item): self {
        \$values = \$this->toArray();
        if((\$key = array_search(\$item->toArray(), \$values)) !== false) {
            unset(\$values[\$key]);
        }
        \$values = array_values(\$values);
        
        return self::fromArray(\$values);
    }
EOT;
            }else{
                $phpcode .= <<< EOT

    public function remove($this->itemType \$item): self {
        \$values = \$this->toArray();
        if((\$key = array_search(\$item, \$values)) !== false) {
            unset(\$values[\$key]);
        }
        \$values = array_values(\$values);
        
        return self::fromArray(\$values);
    }
EOT;
            }
        } else {
            $phpcode .= <<< EOT

    public function add($this->itemType \$item): self {
        array_push(\$this->items,\$item);
        return \$this;
    }

    public function offsetSet(\$offset, \$value) {
        if (empty(\$offset)) {
            array_push(\$this->items, \$value);
        } else {
            \$this->items[\$offset] = \$value;
        }
    }

    public function offsetUnset(\$offset) {
        unset(\$this->items[\$offset]);
        \$this->items = array_values(\$this->items);
    }
    
EOT;
            if (!$this->isPrimitiveType($this->itemType)){
                $phpcode .= <<< EOT

    public function remove($this->itemType \$item): self {
        \$values = \$this->toArray();
        if((\$key = array_search(\$item->toArray(), \$values)) !== false) {
            unset(\$this->items[\$key]);
        }
        
        \$this->items = array_values(\$this->items);
        return \$this;
    }
EOT;
            }else{
                $phpcode .= <<< EOT

    public function remove($this->itemType \$item): self {
        \$values = \$this->toArray();
        if((\$key = array_search(\$item, \$values)) !== false) {
            unset(\$this->items[\$key]);
        }
                
        \$this->items = array_values(\$this->items);
        return \$this;
    }
EOT;
            }
        }

        return $phpcode;
    }
}