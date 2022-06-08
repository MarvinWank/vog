<?php

namespace Vog\Commands\Generate;

use InvalidArgumentException;
use UnexpectedValueException;

use Vog\Exception\VogException;
use Vog\Factories\GeneratorFactory;
use Vog\ValueObjects\CommandOptions;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\GenerateCommandOptions;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogDefinitionFile;
use Vog\ValueObjects\VogDefinitionSet;
use function json_decode;

class GenerateCommand extends AbstractCommand
{
    private GeneratorFactory $generatorFactory;
    private GenerateCommandOptions $generateCommandOptions;
    private string $targetDir;

    public function __construct(Config $config, string $target, GenerateCommandOptions $generateCommandOptions)
    {
        parent::__construct($config);

        $this->targetDir = $target;
        $this->generateCommandOptions = $generateCommandOptions;

        $this->generatorFactory = new GeneratorFactory();
    }

    public function run(): void
    {
        $data = $this->parseFile($this->targetDir);

        /** @var VogDefinition $item */
        foreach ($data->FilePathGroup() as $item) {
            $generator = $this->generatorFactory->buildPhpGenerator(
                $item,
                $this->config->getGeneratorOptions(),
                $data->namespace(),
                $this->getRootDir($data->root_path(), $this->generateCommandOptions->getWorkingDir())
            );
            $this->writeToFile($generator);

            $this->writeIfNotExists($generator->getInterfaceGenerator());
        }
    }

    /** @throws VogException */
    private function getRootDir(string $rootPath, ?string $workingDir): string
    {
        if (!$workingDir) {
            return $rootPath;
        }

        $rootPath = preg_replace('/$\.\//','' , $rootPath);
        $rootDir = realpath($workingDir . DIRECTORY_SEPARATOR . $rootPath);

        if (!$rootDir) {
            throw new VogException("Directory " . $workingDir . DIRECTORY_SEPARATOR . $rootPath .
                " composed off --workingDir Argument with value " . $workingDir .
                " and file-defined root_path wit value " . $rootPath .
                " is not valid"
            );
        }

        return $rootDir;
    }

    public function getCommandOptions(): GenerateCommandOptions
    {
        return $this->generateCommandOptions;
    }

    //TODO: Move to Service
    private function writeToFile(AbstractGenerator $generator)
    {
        return file_put_contents($generator->getAbsoluteFilepath(), $generator->getCode());
    }

    private function writeIfNotExists(AbstractGenerator $generator)
    {
        if (!file_exists($generator->getAbsoluteFilepath())) {
            return file_put_contents($generator->getAbsoluteFilepath(), $generator->getCode());
        }
        return false;
    }
}