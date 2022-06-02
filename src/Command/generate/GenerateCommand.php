<?php

namespace Vog\Commands\Generate;

use InvalidArgumentException;
use UnexpectedValueException;

use Vog\Exception\VogException;
use Vog\Factories\GeneratorFactory;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogDefinitionFile;
use Vog\ValueObjects\VogDefinitionSet;
use function json_decode;

class GenerateCommand extends AbstractCommand
{
    private GeneratorFactory $generatorFactory;
    private string $targetDir;

    public function __construct(Config $config, string $target)
    {
        parent::__construct($config);

        $this->targetDir = $target;
        $this->generatorFactory = new GeneratorFactory();
    }

    public function run(): void
    {
        $data = $this->parseFile($this->targetDir);

        /** @var VogDefinition $item */
        foreach ($data->FilePathGroup() as $item){
            $generator = $this->generatorFactory->buildPhpGenerator(
                $item,
                $this->config->getGeneratorOptions(),
                $data->namespace(),
                $data->root_path()
            );
            $this->writeToFile($generator);

            $this->writeIfNotExists($generator->getInterfaceGenerator());
        }
    }

    //TODO: Move to Service
    private function writeToFile(AbstractGenerator $generator)
    {
        return file_put_contents($generator->getAbsoluteFilepath(), $generator->getCode());
    }

    private function writeIfNotExists(AbstractGenerator $generator)
    {
        if (!file_exists($generator->getAbsoluteFilepath())){
            return file_put_contents($generator->getAbsoluteFilepath(), $generator->getCode());
        }
        return false;
    }
}