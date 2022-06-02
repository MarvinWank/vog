<?php

namespace Unit\Factory;

use Vog\Commands\Generate\AbstractCommand;
use Vog\Commands\Generate\GenerateCommand;
use Vog\Exception\VogException;
use Vog\Factories\CommandFactory;
use Vog\Test\Unit\UnitTestCase;

class CommandFactoryTest extends UnitTestCase
{
    private CommandFactory $commandFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->commandFactory = new CommandFactory();
    }

    public function testBuildGenerateCommand()
    {
        $command = $this->commandFactory->buildCommand('generate', [__DIR__ . '/../../testDataSources/value.json']);

        $this->assertInstanceOf(GenerateCommand::class, $command);
        $this->assertInstanceOf(AbstractCommand::class, $command);
    }

    public function testThrowsExceptionWhenFileDoesNotExist()
    {
        $this->expectException(VogException::class);
        $this->commandFactory->buildCommand('generate', ["./foo.json"]);
    }

    public function testThrowsExceptionWhenNoFileGiven()
    {
        $this->expectException(VogException::class);
        $this->commandFactory->buildCommand('generate');
    }

    public function testParseOptionalArguments()
    {
        $command = $this->commandFactory->buildCommand(
            'generate',
            [
                __DIR__ . '/../../testDataSources/value.json',
                "--workingDir   ./foo",
                "--configFile ./OtherVogConfig.json"
            ]
        );

        $options = $command->getCommandOptions()->toArray();

        $this->assertEquals('./foo', $options['workingDir']);
        $this->assertEquals('./OtherVogConfig.json', $options['configFile']);
    }

    public function testParseOptionalArgument()
    {
        $command = $this->commandFactory->buildCommand(
            'generate',
            [
                __DIR__ . '/../../testDataSources/value.json',
                "--configFile ./OtherVogConfig.json"
            ]
        );

        $options = $command->getCommandOptions();

        $this->assertEquals('./OtherVogConfig.json', $options['configFile']);
    }
}