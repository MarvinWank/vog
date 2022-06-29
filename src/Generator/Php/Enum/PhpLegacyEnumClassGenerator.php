<?php

namespace Vog\Generator\Php\Enum;

use Vog\Generator\Php\Classes\AbstractPhpClassGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

class PhpLegacyEnumClassGenerator extends AbstractPhpClassGenerator
{
    protected array $implements = ['Enum'];

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNamespace, $rootDir);

        $this->values = $this->formatValues($this->values);
    }

    private function formatValues(array $values): array
    {
        $valuesFormatted = [];

        foreach ($values as $name => $value){
            if (is_numeric($name)){
                $valuesFormatted[strtoupper($value)] = $value;
            }
        }

        return $valuesFormatted;
    }

    public function getCode(): string
    {
        $phpcode = $this->phpService->generatePhpClassHeader(
            $this->name,
            $this->getNamespace(),
            ['InvalidArgumentException'],
            false,
            null,
            $this->implements,
        );
        $phpcode .= $this->generateConstNamesAndValues($this->values);
        $phpcode .= $this->generateConstOptions();
        $phpcode .= $this->generateConstructor();
        $phpcode .= $this->generateMethods();
        $phpcode .= $this->generateFromNameFromValue();
        $phpcode .= $this->generateGenericFunctions();
        $phpcode .= $this->closeRootScope();

        return $phpcode;
    }


    protected function generateConstNamesAndValues(array $values): string
    {
        $phpcode = "\n";

        $valuesOnly = implode("', '",$values);
        $namesOnly = implode("', '",array_keys($values));

        $valuesAsString = "['" . $valuesOnly . "']";
        $phpcode .= "    public const VALUES = " . $valuesAsString . ";\n";

        $namesAsString = "['" . $namesOnly . "']";
        $phpcode .= "    public const NAMES = " . $namesAsString . ";\n";

        return $phpcode;
    }

    protected function generateConstOptions(): string
    {
        $phpcode = <<<EOT
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

    protected function generateConstructor(): string
    {
        $phpcode = <<<'EOT'
        
          
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

    protected function generateMethods(): string
    {
        $phpcode = PHP_EOL;
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

    protected function generateFromNameFromValue(): string
    {
        $phpcode = <<<'EOT'
        
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

    protected function generateGenericFunctions(): string
    {
        $phpcode = <<<'EOT'
        
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