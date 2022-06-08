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

    public function __construct(VogDefinition $definition, GeneratorOptions $generatorOptions, string $rootNamespace, string $rootDir)
    {
        parent::__construct($definition, $generatorOptions, $rootDir);

        $this->rootNamespace = $rootNamespace;
        $this->implements = $definition->implements() ?? [];

        $this->phpService = new PhpService();
    }

    public function getAbsoluteFilepath(): string
    {
        $absolutePath = realpath($this->rootDirectory . DIRECTORY_SEPARATOR . $this->subDirectory);
        return $absolutePath . ucfirst($this->name) . ".php";
    }

    protected function getNamespace(): string
    {
        return $this->phpService->getTargetNamespace(
            $this->rootNamespace,
            $this->subDirectory
        );
    }

    protected function closeRootScope(): string
    {
        return <<<EOT

}
EOT;
    }
}