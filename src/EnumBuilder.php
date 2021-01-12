<?php

namespace Vog;

use Vog\ValueObjects\Config;
use Vog\ValueObjects\TargetMode;

class EnumBuilder extends AbstractBuilder
{
    protected array $implements = ['Enum'];

    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
        $this->type = "enum";
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader();
        $phpcode = $this->generateConstOptions($phpcode);
        $phpcode = $this->generateConstructor($phpcode);
        $phpcode = $this->generateMethods($phpcode);
        $phpcode = $this->generateFromNameFromValue($phpcode);
        $phpcode = $this->generateGenericFunctions($phpcode);
        $phpcode = $this->closeClass($phpcode);

        return $phpcode;
    }

    public function setValues(array $values)
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->config->getGeneratorOptions()->getTarget())) {
            $upper=[];
            foreach ($values as $key => $value) {
                $upper[strtoupper($key)] = $value;
            }
            $this->values = $upper;
            return;
        }

        $this->values = $values;
    }

    protected function generateConstOptions(string $phpcode): string
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

    protected function generateConstructor(string $phpcode): string
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

    protected function generateMethods(string $phpcode): string
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

    protected function generateFromNameFromValue(string $phpcode): string
    {
        $phpcode .= <<<'EOT'

    public static function fromValue(string $input_value): self
    {
        foreach (self::OPTIONS as $key => $value) {
            if ($input_value === $value) {
                return new self($key);
            }
        }

        throw new InvalidArgumentException("Unknown enum value '$input_value' given");
    }
    
    public static function fromName(string $name): self
    {
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