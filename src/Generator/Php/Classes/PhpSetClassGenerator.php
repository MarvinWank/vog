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
        $phpcode .= $this->generateFromArray($this->name);
        $phpcode .= $this->generateToArray($this->itemType);
        $phpcode .= $this->generateGenericFunctions();
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

   protected function generateFromArray(string $name): string
    {
        $phpcode = "";

        if (empty($this->itemType)) {
            return $this->setService->generateFromArrayForUnspecifiedType();
        } else if ($this->isPrimitiveType($this->itemType)) {
            return $this->setService->generateFromArrayForPrimitiveType($this->itemType, $name);
        }
        else {
            $phpcode .= <<<EOT
                public static function fromArray(array \$items) {
                    foreach (\$items as \$key => \$item) {
                        \$type = gettype(\$item);
                        switch (\$type) {
                            case 'object':
                                if (!\$item instanceof $this->itemType){
                                    throw new UnexpectedValueException('array expects items of $this->itemType but has ' . \$type . ' on index ' . \$key); 
                                }    
                                break;
                            case 'array':
                                if(is_a($this->itemType::class, ValueObject::class, true) || is_a($this->itemType::class, Set::class, true)) {
                                    \$items[\$key] = $this->itemType::fromArray(\$item);
                                } else {
                                    throw new UnexpectedValueException('fromArray can not create $this->itemType from array on index ' . \$key);
                                }
                                break;    
                            case 'string':
                                if(is_a($this->itemType::class, Enum::class, true)) {
                                    \$items[\$key] = $this->itemType::fromName(\$item);
                                } else {
                                    throw new UnexpectedValueException('fromArray can not create $this->itemType from string on index ' . \$key);
                                }
                                break;    
                            default:
                                if (\$type !== '$this->itemType') {
                                    throw new UnexpectedValueException('fromArray expects items of $this->itemType but has ' . \$type . ' on index ' . \$key);
                                }
                                break;
                        }
                        
                    }
                    return new self(\$items);
                }
            EOT;
        }

        return $phpcode;
    }

    protected function generateGenericFunctions(string $phpcode): string
    {
        $phpcode .= <<<EOT

    public function equals(?self \$other): bool
    {
        \$ref = \$this->toArray();
        \$val = \$other->toArray();
                
        return (\$ref === \$val);
    }    
    
    public function contains($this->itemType \$item): bool {
        return array_search(\$item, \$this->items) !== false;
    }
    
    public function count(): int
    {
        return count(\$this->items);
    }
    
        public function offsetExists(\$offset) {
        return isset(\$this->items[\$offset]);
    }

    public function offsetGet(\$offset) {
        return \$this->items[\$offset];
    }

    public function current() {
        return \$this->items[\$this->position];
    }

    public function rewind() {
        \$this->position = 0;
    }

    public function key() {
        return \$this->position;
    }

    public function next() {
        ++\$this->position;
    }

    public function valid() {
        return isset(\$this->items[\$this->position]);
    }
    
EOT;
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