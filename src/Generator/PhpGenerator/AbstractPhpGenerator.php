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
    protected ?string $dateTimeFormat;
    protected array $implements = [];
    protected bool $isFinal;
    protected bool $isMutable;

    protected PhpService $phpService;

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace)
    {
        parent::__construct($definition, $generatorOptions);

        $this->phpService = new PhpService();

        $this->extends = $definition->extends();
        $this->implements = $definition->implements() ?? [];
        $this->isFinal = $definition->final();
        $this->isMutable = $definition->mutable() ?? false;
        $this->rootNamespace = $rootNamespace;
        $this->dateTimeFormat = $definition->dateTimeFormat();
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

    protected function closeClass(): string
    {
        return <<<EOT

}
EOT;
    }

    public static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    protected function getNamespace(): string
    {
        return $this->phpService->getTargetNamespace(
            $this->rootNamespace,
            $this->directory
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

    public function getAbsoluteFilepath(): string
    {
        return $this->targetFilepath . DIRECTORY_SEPARATOR . ucfirst($this->name) . ".php";
    }

}