<?php


namespace Vog\Commands\Generate;

use Vog\Service\PhpService;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractPhpGenerator extends AbstractGenerator
{
    protected string $rootNamespace;
    protected ?string $extends;
    protected array $implements = [];
    protected bool $is_final;
    protected bool $is_mutable;

    protected PhpService $phpService;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace)
    {
        parent::__construct($definition, $generatorOptions);

        $this->phpService = new PhpService();

        $this->extends = null;
        $this->is_final = false;
        $this->is_mutable = false;
        $this->rootNamespace = $rootNamespace;
    }

    abstract public function getPhpCode(): string;

    public function setValues(array $values): void
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->generatorOptions->getGeneratorOptions()->getTarget())) {
            $camelized = [];
            foreach ($values as $key => $value) {
                $camelized[self::toCamelCase($key)] = $value;
            }

            $this->values = $camelized;
            return;
        }

        $this->values = $values;
    }

    protected function closeClass($phpcode): string
    {
        $phpcode .= <<<EOT

}
EOT;
        return $phpcode;
    }

    public static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    protected function getNamespace(): string
    {
        return $this->phpService->getTargetNamespace(
            $this->rootNamespace,
            $this->definition->directory()
        );
    }

    public function getExtends(): string
    {
        return $this->extends;
    }

    public function setExtends(string $extends): void
    {
        $this->extends = $extends;
    }

    public function getImplements(): array
    {
        return $this->implements;
    }

    public function setImplements(array $implements): void
    {
        // extending classes define default marker interfaces
        $this->implements = array_merge($this->implements, $implements);
    }

    public function isIsFinal(): bool
    {
        return $this->is_final;
    }

    public function setIsFinal(bool $is_final): void
    {
        $this->is_final = $is_final;
    }

    public function isMutable(): bool
    {
        return $this->is_mutable;
    }

    public function setIsMutable(bool $is_mutable): void
    {
        $this->is_mutable = $is_mutable;
    }

    public function getAbsoluteFilepath(): string
    {
        return $this->targetFilepath . DIRECTORY_SEPARATOR . ucfirst($this->name) . ".php";
    }

}