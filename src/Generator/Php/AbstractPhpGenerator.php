<?php

namespace Vog\Generator\Php;

use Vog\Commands\Generate\AbstractGenerator;
use Vog\Service\PhpService;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\VogDefinition;

abstract class AbstractPhpGenerator extends AbstractGenerator
{
    protected PhpService $phpService;

    protected string $rootNamespace;
    protected array $implements = [];

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace)
    {
        parent::__construct($definition, $generatorOptions);

        $this->rootNamespace = $rootNamespace;
        $this->implements = $definition->implements() ?? [];

        $this->phpService = new PhpService();
    }

    public function getAbsoluteFilepath(): string
    {
        return $this->directory . DIRECTORY_SEPARATOR . ucfirst($this->name) . ".php";
    }

    protected function getNamespace(): string
    {
        return $this->phpService->getTargetNamespace(
            $this->rootNamespace,
            $this->directory
        );
    }

    protected function closeRootScope(): string
    {
        return <<<EOT

}
EOT;
    }
}