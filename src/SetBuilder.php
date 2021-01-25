<?php

namespace Vog;

use Vog\ValueObjects\Config;

class SetBuilder extends ValueObjectBuilder
{
    private string $itemType = '';
    protected array $implements = ['Set', '\Countable', '\ArrayAccess', '\Iterator'];

    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
        $this->type = 'set';
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader([
            AbstractBuilder::UNEXPECTED_VALUE_EXCEPTION,
            AbstractBuilder::BAD_METHOD_CALL_EXCEPTION
        ]);
        $phpcode = $this->generateConstructor($phpcode);
        $phpcode = $this->generateFromArray($phpcode);
        $phpcode = $this->generateToArray($phpcode);
        $phpcode = $this->generateGenericFunctions($phpcode);
        $phpcode = $this->closeClass($phpcode);

        return $phpcode;
    }

    /**
     * @param string $itemType
     */
    public function setItemType(string $itemType): void
    {
        $this->itemType = $itemType;
    }

    protected function generateConstructor(string $phpcode): string
    {
        $phpcode .= <<<'EOT'
        
    private array $items;
    private int $position;
        
    private function __construct(array $items = [])
    {
        $this->position = 0;
        $this->items = $items;
    }
EOT;
        return $phpcode;
    }

    protected function generateToArray(string $phpcode): string
    {
        $phpcode .= <<<EOT
        
            public function toArray() {
        EOT;


        if (!in_array($this->itemType, parent::PRIMITIVE_TYPES)) {
            $phpcode .= <<<EOT
                
                        \$return = [];
                        foreach (\$this->items as \$item) {
                            if(is_a($this->itemType::class, ValueObject::class, true) || is_a($this->itemType::class, Set::class, true)) {
                                \$return[] = \$item->toArray();
                            }
                            
                            if(is_a($this->itemType::class, Enum::class, true)) {
                                \$return[] = \$item->toString();
                            }
                        }
                        
                        return \$return;
                EOT;
        } else {
            $phpcode .= <<<EOT
                
                        return \$this->items;
                EOT;
        }

        $phpcode .= <<<EOT
        
            }
        EOT;
        return $phpcode;
    }

    protected function generateFromArray(string $phpcode): string
    {
        if (empty($this->itemType)) {
            $phpcode .= <<<EOT

                public static function fromArray(array \$items) {
                    return new self(\$items);
                }
            EOT;
        } else {
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
    
    public function add($this->itemType \$item): self {
        \$values = \$this->toArray();
        \$values[] = \$item;
        return self::fromArray(\$values);
    }
    
    public function remove($this->itemType \$item): self {
        \$values = \$this->toArray();
        if((\$key = array_search(\$item->toArray(), \$values)) !== false) {
            unset(\$values[\$key]);
        }
        
        return self::fromArray(\$values);
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

    public function offsetSet(\$offset, \$value) {
        throw new BadMethodCallException('ArrayAccess offsetSet is forbidden, use ->add()');
    }

    public function offsetGet(\$offset) {
        return \$this->items[\$offset];
    }

    public function offsetUnset(\$offset) {
        throw new BadMethodCallException('ArrayAccess offsetUnset is forbidden, use ->remove()');
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
        return $phpcode;
    }
}