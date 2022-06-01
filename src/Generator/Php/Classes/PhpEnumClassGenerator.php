<?php

namespace Vog\Generator\Php\Classes;

use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\VogDefinition;

class PhpEnumClassGenerator extends AbstractPhpClassGenerator
{
    protected array $implements = ['Enum'];

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace)
    {
        parent::__construct($definition, $generatorOptions, $rootNamespace);
        $this->definition = $this->definition->with_values($this->formatValues($this->definition->values()));
    }

    public function getCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader([AbstractGenerator::INVALID_ARGUMENT_EXCEPTION]);
        $phpcode = $this->generateConstNamesAndValues($phpcode);
        $phpcode = $this->generateConstOptions($phpcode);
        $phpcode = $this->generateConstructor($phpcode);
        $phpcode = $this->generateMethods($phpcode);
        $phpcode = $this->generateFromNameFromValue($phpcode);
        $phpcode = $this->generateGenericFunctions($phpcode);
        $phpcode = $this->closeRootScope($phpcode);

        return $phpcode;
    }

    private function formatValues(array $values): array
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getTarget())) {
            $upper = [];
            foreach ($values as $key => $value) {
                $upper[strtoupper($key)] = $value;
            }
            return $upper;
        }

        return $values;
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

    protected function generateConstNamesAndValues($phpcode): string
    {
        $valuesOnly = "";
        $namesOnly = "";

        foreach ($this->values as $name => $value) {
            $valuesOnly .= "'".$value."', ";
            $namesOnly .= "'".$name."', ";
        }

        $valuesAsString = "[" . $valuesOnly . "]";
        $phpcode .= <<<EOT

                public const VALUES = $valuesAsString;               
            EOT;

        $namesAsString = "[" . $namesOnly . "]";
        $phpcode .= <<<EOT

                public const NAMES = $namesAsString;               
            EOT;

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
        
            public static function fromValue(string $value): self
            {
                foreach (self::OPTIONS as $key => $option) {
                    if ($value === $option) {
                        return new self($key);
                    }
                }
        
                throw new InvalidArgumentException("Unknown enum value '$value' given");
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