<?php

namespace Vog;

class SetBuilder extends ValueObjectBuilder
{
    private string $itemType = '';

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->type = 'set';
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader();
        $phpcode = $this->generateConstructor($phpcode);
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
        
    private array $items = [];
        
    private function __construct(array $items)
    {
        $this->items = $items;
    }
EOT;
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

    public function toArray() {
        return \$this->items;
    }
    
    public static function fromArray(array \$items) {
        return new self(\$items);
    }

    public function count(): int
    {
        return count(\$this->items);
    }

    public function add($this->itemType \$item) {
        \$this->items[] = \$item;
    }
    
    public function remove($this->itemType \$item) {
        if((\$key = array_search(\$item, \$this->items)) !== false) {
            unset(\$this->items[\$key]);
        }
    }
    
    public function contains($this->itemType \$item) {
        if((\$key = array_search(\$item, \$this->items)) !== false) {
            return true;
        }
        
        return false;
    }
    
EOT;
        return $phpcode;
    }
}