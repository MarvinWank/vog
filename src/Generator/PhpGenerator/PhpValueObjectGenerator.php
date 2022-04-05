<?php


namespace Vog\Commands\Generate;

use UnexpectedValueException;
use Vog\Exception\VogException;
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
        $phpcode = $this->phpService->generateGenericPhpHeader(
            $this->definition->name(),
            $this->getNamespace()
        );
        $phpcode .= $this->generateProperties($this->getValues());

        $phpcode .= $this->generateConstructor($this->getValues());
        $phpcode .= $this->generateGetters($this->getValues());

        if ($this->is_mutable) {
            $phpcode .= $this->generateSetters($this->getValues());
        }

        $phpcode .= $this->generateWithMethods($this->getValues());
        $phpcode .= $this->generateToArray($this->getValues());
        $phpcode .= $this->generateFromArray($this->getValues());
        if ($this->generatorOptions->getToArrayMode()->equals(ToArrayMode::DEEP())){
            $phpcode .= $this->generateValueToArray($phpcode);
        }
        $phpcode .= $this->generateEquals();

        if (isset($this->stringValue)) {
            $phpcode .= $this->generateToString();
        }

        $phpcode .= $this->closeClass($phpcode);
        return $phpcode;
    }

    private function generateProperties(array $values): string
    {
        $phpcode = "";
        foreach ($values as $name => $data_type) {
            $phpcode .= <<<EOT
            
                private $data_type $$name;
            EOT;
        }

        return $phpcode;
    }

    /**
     * @throws VogException
     */
    protected function getValues(): array
    {
        $values = parent::getValues();

        if ($values === null){
            $name = $this->definition->name();
            throw new VogException("No values where specified for value object '$name'");
        }

        return $values;
    }

    private function generateConstructor(array $values): string
    {
        return $this->phpService->generateConstructor($values);
    }

    private function generateGetters(array $values): string
    {
        return $this->phpService->generateGetters($values);
    }

    private function generateSetters(array $values): string
    {
        $phpcode = "";
        foreach ($values as $name => $data_type) {
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

    private function generateWithMethods(array $values): string
    {
        $phpcode = "";
        foreach ($values as $name => $data_type) {
            $functionName = $this->getWithFunctionName($name);
            $phpcode .= <<<EOT
            
                public function $functionName($data_type $$name): self 
                {
                    return new self(
            EOT;

            foreach ($values as $name_assigner => $data_type_assginer) {
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

    protected function generateToArray(array $values): string
    {
        $phpcode = <<<EOT
        
            public function toArray(): array
            {
                return [
        EOT;

        if ($this->generatorOptions->getToArrayMode()->equals(ToArrayMode::DEEP())) {

            foreach ($values as $name => $datatype) {
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

        } elseif ($this->generatorOptions->getToArrayMode()->equals(ToArrayMode::SHALLOW())) {

            foreach ($values as $name => $datatype) {
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

    protected function generateFromArray(array $values): string
    {
        $phpcode = <<<'EOT'
        
            public static function fromArray(array $array): self
            {
        EOT;

        foreach ($values as $name => $datatype) {

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

        foreach ($values as $name => $datatype) {
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

    private function generateToString(): string
    {
        return $this->phpService->generateToStringMethod($this->stringValue);
    }

    private function generateValueToArray(string $dateTimeFormat): string
    {
        return $this->phpService->generateValueToArrayMethod($dateTimeFormat);
    }

    //TODO: Rework
    private function generateEquals(): string
    {
        return <<<'EOT'
            
            public function equals($value): bool
            {
                $ref = $this->toArray();
                $val = $value->toArray();
                
                return ($ref === $val);
            }
            
        EOT;
    }

    private function getWithFunctionName(string $name): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getTarget())) {
            return 'with' . ucfirst($name);
        }

        return 'with_' . $name;
    }

    private function getSetter(string $name): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getTarget())) {
            return 'set' . ucfirst($name);
        }

        return 'set_' . $name;
    }

}