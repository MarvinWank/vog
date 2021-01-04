<?php

namespace Vog;

class Enum extends VogDataObject
{

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->type = "enum";
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader();
        $phpcode = $this->generate_const_options($phpcode);
        $phpcode = $this->generate_constructor($phpcode);
        $phpcode = $this->generate_methods($phpcode);
        $phpcode = $this->generate_from_name_from_value($phpcode);
        $phpcode = $this->generate_generic_functions($phpcode);
        $phpcode = $this->closeClass($phpcode);

        return $phpcode;
    }

    protected function generate_const_options(string $phpcode): string
    {
        $phpcode .= <<<EOT
        
            public const OPTIONS = [ 
        EOT;

        foreach ($this->values as $name => $value) {
            $phpcode .= <<<EOT
            
                    '$name' => '$value',
            EOT;
        }
        $phpcode .= <<<EOT
        
            ];
        EOT;

        $phpcode .= PHP_EOL;
        foreach ($this->values as $name => $value) {
            $phpcode .= <<<EOT

                public const $name = '$value';               
            EOT;
        }
        return $phpcode;
    }

    protected function generate_constructor(string $phpcode): string
    {
        $phpcode .= <<<'EOT'
        
    private string $name;
    private string $value;
        
    private function __construct(string $name)
    {
        $this->name = $name;
        $this->value = self::OPTIONS[$name];
    }
EOT;
        return $phpcode;
    }

    protected function generate_methods(string $phpcode): string
    {
        $phpcode .= PHP_EOL;
        foreach ($this->values as $name => $value) {
            $phpcode .= <<<EOT

    public static function $name(): self
    {
        return new self('$name');
    }
EOT;
        }
        return $phpcode;
    }

    protected function generate_from_name_from_value(string $phpcode): string
    {
        $phpcode .= <<<'EOT'

    public static function fromValue(string $input_value): self
    {
        foreach (self::OPTIONS as $key => $value) {
            if ($input_value === $value) {
                return new self($key);
            }
        }

        throw new \InvalidArgumentException("Unknown enum value '$input_value' given");
    }
    
    public static function fromName(string $name): self
    {
        if(!array_key_exists($name, self::OPTIONS)){
             throw new \InvalidArgumentException("Unknown enum name $name given");
        }
        
        return new self($name);
    }
    
EOT;
        return $phpcode;
    }

    protected function generate_generic_functions(string $phpcode): string
    {
        $phpcode .= <<<'EOT'

    public function equals(?self $other): bool
    {
        return (null !== $other) && ($this->name() === $other->name());
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name;
    }
EOT;
        return $phpcode;
    }
}