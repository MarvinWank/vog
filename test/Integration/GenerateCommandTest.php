<?php

namespace Integration;

use Vog\Commands\Generate\GenerateCommand;
use Vog\Exception\VogException;
use Vog\ValueObjects\GenerateCommandOptions;

class GenerateCommandTest extends IntegrationTestCase
{

    public function testRunGenerateCommand()
    {
        $config = $this->generateConfig();
        $commandOptions = new GenerateCommandOptions(null, null);
        $command = new GenerateCommand($config, __DIR__ . '/../testDataSources/value.json', $commandOptions);

        $command->run();
    }
}