<?php

namespace Vog\Test\Unit\Command;

use Vog\Commands\Generate\GenerateCommand;
use Vog\Exception\VogException;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\GenerateCommandOptions;

class GenerateCommandTest extends UnitTestCase
{

    public function testGetRootDir()
    {
        $commandOptions = new GenerateCommandOptions(__DIR__, null);
        $config = $this->generateConfig();
        $command = new GenerateCommand($config, __DIR__ . '/../testDataSources/value.json', $commandOptions);

        $rootDir = $this->callProtectedMethod($command, 'getRootDir',  ['../../testDataSources', __DIR__]);

        $expected = realpath(__DIR__ . '/../../testDataSources');
        $this->assertEquals($expected, $rootDir);
    }

    public function testThrowExceptionWhenDirectoryInvalid()
    {
        $commandOptions = new GenerateCommandOptions(__DIR__, null);
        $config = $this->generateConfig();
        $command = new GenerateCommand($config, __DIR__ . '/../testDataSources/value.json', $commandOptions);

        $this->expectException(VogException::class);
        $rootDir = $this->callProtectedMethod($command, 'getRootDir',  ['./Foo', __DIR__]);

        $expected = realpath(__DIR__ . '/Generator');
        $this->assertEquals($expected, $rootDir);
    }
}