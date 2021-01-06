<?php


namespace Vog;


use UnexpectedValueException;

class CommandHub
{
    private const COMMAND_GENERATE = "generate";
    private const COMMAND_FPP_CONVERT = "fpp_convert";

    private const COMMANDS = [self::COMMAND_GENERATE, self::COMMAND_FPP_CONVERT];

    public function run(array $argv, ?array $config = null)
    {
        if (!isset($argv[1])) {
            $this->printUsage();
            exit;
        }

        switch ($argv[1]){
            case self::COMMAND_GENERATE: $this->runGenerateCommand($argv[2], $config);
                break;
            case self::COMMAND_FPP_CONVERT: $this->runConvertToFppCommand($argv[3], $argv[4], $config);
                break;
            default:
                throw new UnexpectedValueException("Command $argv is not defined. Defined commands are: "
                . implode(", ", self::COMMANDS));
        }
    }

    private function runGenerateCommand(string $targetPath, ?array $config = null)
    {
        if ($config === null) {
            $configFile = getcwd() . '/vog_config.php';
            if (file_exists($configFile)) {
                echo "reading " . $configFile;
                $config = require($configFile);
            } else {
                echo "reading default config";
                $config = require(__DIR__.'/../vog_default_config.php');
            }
        }

        $generate = new Generate($config);
        $generate->run($targetPath);
    }

    private function runConvertToFppCommand(string $fileToConvert, ?string $outputPath = null, array $config)
    {
        $fppConvert = new FppConvert($config);
        $fppConvert->run($fileToConvert, $outputPath);
    }

    private function printUsage() {
        print("Value Object Generator".PHP_EOL);
        print("generates PHP value objects from a json file.".PHP_EOL);
        print(PHP_EOL);
        print("Usage: ".PHP_EOL);
        print("vendor/bin/vog [command] [path/to/definitionfile.json]".PHP_EOL);
        print(PHP_EOL);
        print("Commandas:".PHP_EOL);
        print("\tgenerate".PHP_EOL);
    }
}