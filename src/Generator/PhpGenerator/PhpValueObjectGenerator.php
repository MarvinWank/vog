<?php


namespace Vog\Commands\Generate;

use UnexpectedValueException;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;
use Vog\ValueObjects\VogDefinition;

class PhpValueObjectGenerator extends AbstractPhpGenerator
{
    private string $stringValue;
    private ?string $dateTimeFormat;

    private const INTERFACE_NAME = "ValueObject";
    protected array $implements = [self::INTERFACE_NAME];

    public function __construct(VogDefinition $definition, GeneratorOptions $options)
    {
        parent::__construct($definition, $options);
    }

    public function setValues(array $values): void
    {
        parent::setValues($values);
        if (!$this->isMutable()) {
            $this->checkForDateTimeInsteadOfDateTimeImmutable($this->values);
        }
    }

    private function checkForDateTimeInsteadOfDateTimeImmutable(array $values): void
    {
        $objectName = $this->name;
        foreach ($values as $name => $dataType) {
            if ($dataType === "DateTime") {
                throw new UnexpectedValueException("
                Error parsing property $name of object $objectName:
                DateTime ist not allowed when value object is not declared mutable.
                Use DateTimeImmutable or declare mutability.
                ");
            }
        }
    }

    public function setStringValue(string $stringValue)
    {
        if (!array_key_exists($stringValue, $this->values)) {
            throw new UnexpectedValueException("Designated string value $stringValue does not exist in values: " . print_r(array_keys($this->values), true));
        }

        $this->stringValue = $stringValue;
    }

    public function setDateTimeFormat(string $dateTimeFormat)
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->genericPhpHelper->generateGenericPhpHeader(
            $this->definition->name(),
        )

        $phpcode = $this->generateGenericPhpHeader([AbstractGenerator::UNEXPECTED_VALUE_EXCEPTION]);
        $phpcode = $this->generateProperties($phpcode);

        $phpcode = $this->generateConstructor($phpcode);
        $phpcode = $this->generateGetters($phpcode);

        if ($this->is_mutable) {
            $phpcode = $this->generateSetters($phpcode);
        }

        $phpcode = $this->generateWithMethods($phpcode);
        $phpcode = $this->generateToArray($phpcode);
        $phpcode = $this->generateFromArray($phpcode);
        if ($this->generatorOptions->getGeneratorOptions()->getToArrayMode()->equals(ToArrayMode::DEEP())){
            $phpcode = $this->generateValueToArray($phpcode);
        }
        $phpcode = $this->generateEquals($phpcode);

        if (isset($this->stringValue)) {
            $phpcode = $this->generateToString($phpcode);
        }


        $phpcode = $this->closeClass($phpcode);
        return $phpcode;
    }

    private function generateProperties(string $phpcode): string
    {
        foreach ($this->values as $name => $data_type) {
            $phpcode .= <<<EOT
            
                private $data_type $$name;
            EOT;
        }

        return $phpcode;
    }

    private function generateConstructor(string $phpcode): string
    {
        $phpcode .= <<<EOT
        
        
            public function __construct (
        EOT;

        foreach ($this->values as $name => $data_type) {
            $phpcode .= <<<EOT
            
                    $data_type $$name,
            EOT;
        }

        $phpcode = rtrim($phpcode, ',');
        $phpcode .= <<<ETO
        
            ) {
        ETO;
        foreach ($this->values as $name => $data_type) {
            $phpcode .= <<<ETO
            
                    \$this->$name = $$name;
            ETO;
        }
        $phpcode .= <<<ETO
        
            }
            
        ETO;

        return $phpcode;
    }

    private function generateGetters(string $phpcode): string
    {
        foreach ($this->values as $name => $data_type) {
            $functionName = $this->getGetterName($name);

            if ($data_type) {
                $phpcode .= <<<ETO
                
                    public function $functionName(): $data_type 
                    {
                ETO;
            } else {
                $phpcode .= <<<ETO
                
                    public function $functionName() 
                    {
                ETO;
            }
            $phpcode .= <<<EOT
            
                    return \$this->$name;
                }
                
            EOT;
        }
        return $phpcode;
    }

    private function generateSetters(string $phpcode): string
    {
        foreach ($this->values as $name => $data_type) {
            $functionName = $this->getSetter($name);
            if ($data_type) {
                $phpcode .= <<<EOT
                
                    public function $functionName($data_type $$name) 
                    {
                EOT;
            } else {
                $phpcode .= <<<EOT
                
                    public function $functionName($$name) 
                    {
                EOT;
            }
            $phpcode .= <<<EOT
            
                    \$this->$name = $$name;
                }
                
            EOT;
        }
        return $phpcode;
    }

    private function generateWithMethods(string $phpcode): string
    {
        foreach ($this->values as $name => $data_type) {
            $functionName = $this->getWithFunctionName($name);
            $phpcode .= <<<EOT
            
                public function $functionName($data_type $$name): self 
                {
                    return new self(
            EOT;

            foreach ($this->values as $name_assigner => $data_type_assginer) {
                if ($name_assigner === $name) {
                    $phpcode .= <<<EOT
                    
                                $$name_assigner,
                    EOT;

                    continue;
                }
                $phpcode .= <<<EOT
                
                            \$this->$name_assigner,
                EOT;
            }
            $phpcode = rtrim($phpcode, ',');
            $phpcode .= <<<EOT

                    );
                }
                
            EOT;
        }

        return $phpcode;
    }

    protected function generateToArray(string $phpcode): string
    {
        $phpcode .= <<<EOT
        
            public function toArray(): array
            {
                return [
        EOT;

        if ($this->generatorOptions->getGeneratorOptions()->getToArrayMode()->equals(ToArrayMode::DEEP())) {

            foreach ($this->values as $name => $datatype) {
                if (!$this->isPrimitivePhpType($datatype)) {
                    $phpcode .= <<<EOT
                
                            '$name' =>  \$this->valueToArray(\$this->$name),
                EOT;
                } else {
                    $phpcode .= <<<EOT
                
                            '$name' => \$this->$name,
                EOT;
                }
            }

        } elseif ($this->generatorOptions->getGeneratorOptions()->getToArrayMode()->equals(ToArrayMode::SHALLOW())) {

            foreach ($this->values as $name => $datatype) {
                $phpcode .= <<<EOT
                
                            '$name' => \$this->$name,
                EOT;
            }
        } else {
            throw new \UnexpectedValueException("Unexpected Config value for toArrayMode");
        }

        $phpcode .= <<<EOT
        
                ];
            }
            
        EOT;

        return $phpcode;
    }

    protected
    function generateFromArray(string $phpcode): string
    {
        $phpcode .= <<<'EOT'
        
            public static function fromArray(array $array): self
            {
        EOT;

        foreach ($this->values as $name => $datatype) {

            if (!$this->isDataTypeNullable($datatype)){
                $phpcode .= <<<EOT
                    
                    if (!array_key_exists('$name', \$array)) {
                        throw new UnexpectedValueException('Array key $name does not exist');
                    }
                    
            EOT;
            }

            $datatype = $this->sanitizeNullableDatatype($datatype);

            if ($datatype === "\\DateTime") {
                $phpcode .= <<<EOT
                        
                        if (is_string(\$array['$name'])){
                            \$array['$name'] = \\DateTime::createFromFormat('$this->dateTimeFormat', \$array['$name']);
                        }
                EOT;
            } elseif ($datatype === "\\DateTimeImmutable") {
                $phpcode .= <<<EOT
                    
                        if (is_string(\$array['$name'])){
                            \$array['$name'] = \\DateTimeImmutable::createFromFormat('$this->dateTimeFormat', \$array['$name']);
                        }
                        
                EOT;
            } elseif (!$this->isPrimitivePhpType($datatype)) {
                $phpcode .= <<<EOT
                
                        if (isset(\$array['$name']) && is_string(\$array['$name']) && is_a($datatype::class, Enum::class, true)) {
                            \$array['$name'] = $datatype::fromName(\$array['$name']);
                        }
                    
                        if (isset(\$array['$name']) && is_array(\$array['$name']) && (is_a($datatype::class, Set::class, true) || is_a($datatype::class, ValueObject::class, true))) {
                            \$array['$name'] = $datatype::fromArray(\$array['$name']);
                        }

                EOT;
            }
        }

        $phpcode .= <<<EOT
        
                return new self(
        EOT;

        foreach ($this->values as $name => $datatype) {
            $phpcode .= <<<EOT
            
                        \$array['$name'] ?? null,
            EOT;
        }

        $phpcode = rtrim($phpcode, ',');
        $phpcode .= <<<EOT
        
                );
            }
            
        EOT;

        return $phpcode;
    }

    private
    function generateToString(string $phpcode)
    {
        $phpcode .= <<<EOT
        
            public function __toString(): string
            {
                return \$this->toString();
            }
            
            public function toString(): string
            {
                return (string) \$this->$this->stringValue;
            }
            
        EOT;

        return $phpcode;
    }

    private
    function generateValueToArray(string $phpcode)
    {
        $dateTimeFormat = $this->dateTimeFormat;
        $phpcode .= <<<EOT
            
            private function valueToArray(\$value)
            {
                if(\$value === null){
                    return null;
                }
            
                if (method_exists(\$value, 'toArray')) {
                    return \$value->toArray();
                }
                
                if(is_a(\$value, \DateTime::class, true) || is_a(\$value, \DateTimeImmutable::class, true)){
                    return \$value->format('$dateTimeFormat');
                }
                
                return (string) \$value;
            }
            
        EOT;

        return $phpcode;
    }

    private
    function generateEquals(string $phpcode)
    {
        $phpcode .= <<<'EOT'
            
            public function equals($value): bool
            {
                $ref = $this->toArray();
                $val = $value->toArray();
                
                return ($ref === $val);
            }
            
        EOT;

        return $phpcode;
    }

    private
    function getGetterName(string $name): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getGeneratorOptions()->getTarget())) {
            return 'get' . ucfirst($name);
        }

        return $name;
    }

    private
    function getWithFunctionName(string $name): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getGeneratorOptions()->getTarget())) {
            return 'with' . ucfirst($name);
        }

        return 'with_' . $name;
    }

    private
    function getSetter(string $name): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getGeneratorOptions()->getTarget())) {
            return 'set' . ucfirst($name);
        }

        return 'set_' . $name;
    }
}