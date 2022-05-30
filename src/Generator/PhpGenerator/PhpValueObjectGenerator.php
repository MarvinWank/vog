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

    private const INTERFACE_NAME = "ValueObject";
    protected array $implements = [self::INTERFACE_NAME];
    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';
    protected const USE_EXCEPTIONS = [self::UNEXPECTED_VALUE_EXCEPTION, self::INVALID_ARGUMENT_EXCEPTION];

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

    /**
     * @throws VogException
     */
    public function getPhpCode(): string
    {
        $phpcode = $this->phpService->generateGenericPhpHeader(
            $this->name,
            $this->getNamespace(),
            self::USE_EXCEPTIONS
        );
        $phpcode .= $this->generateProperties($this->getValues());

        $phpcode .= $this->generateConstructor($this->getValues());
        $phpcode .= $this->generateGetters($this->getValues(), $this->generatorOptions);

        if ($this->is_mutable) {
            $phpcode .= $this->generateSetters($this->getValues(), $this->generatorOptions);
        }

        $phpcode .= $this->generateWithMethods($this->getValues(), $this->generatorOptions);
        $phpcode .= $this->generateToArray($this->getValues());
        $phpcode .= $this->generateFromArray($this->getValues(), $this->dateTimeFormat);
        if ($this->generatorOptions->getToArrayMode()->equals(ToArrayMode::DEEP())){
            $phpcode .= $this->generateValueToArray($this->dateTimeFormat);
        }
        $phpcode .= $this->generateEquals();

        if (isset($this->stringValue)) {
            $phpcode .= $this->generateToString();
        }

        $phpcode .= $this->closeClass();
        return $phpcode;
    }

    private function generateProperties(array $values): string
    {
        $phpcode = "";
        foreach ($values as $name => $dataType) {
            $phpcode .= <<<EOT
            
                private $dataType $$name;
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

    private function generateGetters(array $values, GeneratorOptions $generatorOptions): string
    {
        return $this->phpService->generateGetters($values, $generatorOptions);
    }

    private function generateSetters(array $values, GeneratorOptions $generatorOptions): string
    {
        return $this->phpService->generateSetters($values, $generatorOptions);
    }

    private function generateFromArray(array $values, string $dateTimeFormat): string
    {
        return $this->phpService->generateFromArray($values, $dateTimeFormat);
    }

    private function generateWithMethods(array $values, GeneratorOptions $generatorOptions): string
    {
        return $this->phpService->generateWithMethods($values, $generatorOptions);
    }

    protected function generateToArray(array $values): string
    {
        return $this->phpService->generateToArray($values, $this->generatorOptions);
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


}