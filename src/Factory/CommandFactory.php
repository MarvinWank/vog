<?php


namespace Vog\Factories;


use UnexpectedValueException;
use Vog\Commands\Generate\AbstractCommand;
use Vog\Commands\Generate\GenerateCommand;
use Vog\Commands\GenerateTypescript\GenerateTypescriptCommand;
use Vog\FppConvertCommand;
use Vog\ValueObjects\Config;
use VogException;

class CommandFactory
{
    private const COMMAND_GENERATE = "generate";
    private const COMMAND_GENERATE_TYPESCRIPT = "generate-typescript";
    private const COMMAND_FPP_CONVERT = "fpp-convert";

    private const COMMANDS = [
        self::COMMAND_GENERATE,
        self::COMMAND_FPP_CONVERT,
        self::COMMAND_GENERATE_TYPESCRIPT
    ];

    /**
     * @throws VogException
     */
    public function buildCommand(string $commandString, array $additionalArguments = [], Config $config = null): AbstractCommand
    {
        if ($config === null) {
            $configFactory = new ConfigFactory();
            $config = $configFactory->buildConfig();
        }

        switch ($commandString) {
            case self::COMMAND_GENERATE:
                return $this->buildGenerateCommand($config, $additionalArguments);
            case self::COMMAND_GENERATE_TYPESCRIPT:
                return new GenerateTypescriptCommand($config);
            case self::COMMAND_FPP_CONVERT:
                return new FppConvertCommand($config);
            default:
                throw new UnexpectedValueException("Command $commandString is not defined. Defined commands are: "
                    . implode(", ", self::COMMANDS));
        }
    }

    /**
     * @throws VogException
     */
    private function buildGenerateCommand(Config $config, array $argv): GenerateCommand
    {
        if (!isset($argv[0])){
            throw new VogException("No target was given");
        }
        $target = $argv[0];
        if (!file_exists($target)){
            throw new VogException("No file was found at $target");
        }

        return new GenerateCommand($config, $target);
    }
}