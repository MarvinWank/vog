<?php

use Vog\Commands\Generate\GenerateCommand;

class GenerateCommandTest extends IntegrationTestCase
{

    public function testRunGenerateCommand()
    {
        $config = $this->generateConfig();
        $command = new GenerateCommand($config, __DIR__ . '/../testDataSources/value.json');

        $command->run();
    }
}