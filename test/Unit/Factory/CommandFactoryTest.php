<?php

namespace Unit\Factory;

use Unit\UnitTestCase;
use Vog\Commands\Generate\AbstractCommand;
use Vog\Commands\Generate\GenerateCommand;
use Vog\Factories\CommandFactory;
use VogException;

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
        $command = $this->commandFactory->buildCommand('generate', null, [__DIR__ . '/../../testDataSources/value.json']);

        $this->assertInstanceOf(GenerateCommand::class, $command);
        $this->assertInstanceOf(AbstractCommand::class, $command);
    }

    public function testThrowsExceptionWhenFileDoesNotExist()
    {
        $this->expectException(VogException::class);
        $this->commandFactory->buildCommand('generate', null, ["./foo.json"]);
    }

    public function testThrowsExceptionWhenNoFileGiven()
    {
        $this->expectException(VogException::class);
        $this->commandFactory->buildCommand('generate');
    }
}