<?php


namespace Vog\Generator\Php\Classes;

use Vog\Factories\GeneratorFactory;
use Vog\Generator\Php\AbstractPhpGenerator;
use Vog\Generator\Php\AbstractPhpVogDefinitionObjectGenerator;
use Vog\Generator\Php\Interfaces\AbstractPhpInterfaceGenerator;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractPhpClassGenerator extends AbstractPhpVogDefinitionObjectGenerator
{


    protected ?string $extends;
    protected ?string $dateTimeFormat;
    protected bool $isFinal;
    protected bool $isMutable;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootNamespace, $rootDir);

        $this->extends = $definition->extends();
        $this->isFinal = $definition->final() ?? false;
        $this->isMutable = $definition->mutable() ?? false;
        $this->rootNamespace = $rootNamespace;
        $this->dateTimeFormat = $definition->dateTimeFormat() ?? $generatorOptions->getDateTimeFormat();
    }

    public function setValues(array $values): void
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getTarget())) {
            $camelized = [];
            foreach ($values as $key => $value) {
                $camelized[self::toCamelCase($key)] = $value;
            }

            $this->values = $camelized;
            return;
        }

        $this->values = $values;
    }

    public static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }


    public function getExtends(): string
    {
        return $this->extends;
    }

    public function setExtends(string $extends): void
    {
        $this->extends = $extends;
    }

    public function isIsFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): void
    {
        $this->isFinal = $isFinal;
    }

    public function isMutable(): bool
    {
        return $this->isMutable;
    }

    public function setIsMutable(bool $isMutable): void
    {
        $this->isMutable = $isMutable;
    }

}