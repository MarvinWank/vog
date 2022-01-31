<?php


namespace Vog\Factories;


use UnexpectedValueException;
use Vog\Commands\Generate\AbstractCommand;
use Vog\Commands\Generate\GenerateCommand;
use Vog\Commands\GenerateTypescript\GenerateTypescriptCommand;
use Vog\ValueObjects\Config;

class CommandFactory
{
    private const COMMAND_GENERATE = "generate";
    private const COMMAND_GENERATE_TYPESCRIPT = "generate-typescript";
    private const COMMAND_FPP_CONVERT = "fpp-convert";

    private const COMMANDS = [self::COMMAND_GENERATE, self::COMMAND_FPP_CONVERT, self::COMMAND_GENERATE_TYPESCRIPT];

    public function buildCommand(string $commandString, Config $config = null): AbstractCommand
    {
        if ($config === null) {
            $configFactory = new ConfigFactory();
            $config = $configFactory->buildConfig();
        }

        switch ($commandString) {
            case self::COMMAND_GENERATE:
                $this->runGenerateCommand($argv[2], $config);
                break;
            case self::COMMAND_GENERATE_TYPESCRIPT:
                $this->runGenerateTypescriptCommand($argv[2], $argv[3], $config);
                break;
            case self::COMMAND_FPP_CONVERT:
                $this->runConvertToFppCommand($argv[3], $argv[4], $config);
                break;
            default:
                throw new UnexpectedValueException("Command $argv is not defined. Defined commands are: "
                    . implode(", ", self::COMMANDS));
        }
    }

    private function runGenerateCommand(string $targetPath, array $config = []): void
    {
        $configObject = $this->getConfig($config);

        $generate = new GenerateCommand($configObject);
        $generate->run($targetPath);
    }

    private function runGenerateTypescriptCommand(string $sourcePath, string $targetPath, array $config = []): void
    {
        $configObject = $this->getConfig($config);

        $generateTypescript = new GenerateTypescriptCommand($configObject);
        $generateTypescript->run($sourcePath, $targetPath);
    }

    private function runConvertToFppCommand(string $fileToConvert, ?string $outputPath = null, array $config): void
    {
        $fppConvert = new FppConvertCommand($config);
        $fppConvert->run($fileToConvert, $outputPath);
    }

}