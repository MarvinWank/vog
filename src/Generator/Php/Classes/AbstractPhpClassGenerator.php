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
    protected const PHP_PRIMITIVE_TYPES = ["", "string", "?string", "int", "?int", "float", "?float", "bool", "?bool", "array", "?array"];

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

    protected function isPrimitiveType(string $type): bool
    {
        return in_array($type, self::PHP_PRIMITIVE_TYPES);
    }

}