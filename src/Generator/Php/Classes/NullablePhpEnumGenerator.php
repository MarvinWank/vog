<?php

namespace Vog\Generator\Php\Classes;

class NullablePhpEnumGenerator extends PhpEnumClassGenerator
{
    protected function generateConstructor(string $phpcode): string
    {
        $phpcode .= <<<'EOT'
        
                
            private ?string $name;
            private ?string $value;
                
            private function __construct(?string $name)
            {
                if(is_null($name)){
                    $this->name = null;
                    $this->value = null;
                } else {
                    $this->name = $name;
                    $this->value = self::OPTIONS[$name];
                }
            }
        EOT;
        return $phpcode;
    }

    protected function generateFromNameFromValue(string $phpcode): string
    {
        $phpcode .= <<<'EOT'
        
            public static function fromValue(?string $value): self
            {
                if(is_null($value)){
                    return new self(null);
                }
            
                foreach (self::OPTIONS as $key => $option) {
                    if ($value === $option) {
                        return new self($key);
                    }
                }
        
                throw new InvalidArgumentException("Unknown enum value '$value' given");
            }
            
            public static function fromName(?string $name): self
            {
                if(is_null($name)){
                    return new self(null);
                }
            
                if(!array_key_exists($name, self::OPTIONS)){
                     throw new InvalidArgumentException("Unknown enum name $name given");
                }
                
                return new self($name);
            }
        EOT;
        return $phpcode;
    }

    protected function generateGenericFunctions(string $phpcode): string
    {
        $phpcode .= <<<'EOT'
        
        
            public function equals(?self $other): bool
            {
                if($this->value === null && $other === null){
                    return true;
                }
                return ($other !== null) && ($this->name() === $other->name());
            }
        
            public function name(): ?string
            {
                return $this->name;
            }
        
            public function value(): ?string
            {
                return $this->value;
            }
        
            public function __toString(): string
            {
                return $this->name;
            }
        
            public function toString(): ?string
            {
                return $this->name;
            }
        EOT;
        return $phpcode;
    }
}