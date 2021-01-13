<?php


namespace Vog;


use UnexpectedValueException;
use Vog\ValueObjects\Config;

class CommandHub
{
    private const COMMAND_GENERATE = "generate";
    private const COMMAND_FPP_CONVERT = "fpp_convert";

    private const COMMANDS = [self::COMMAND_GENERATE, self::COMMAND_FPP_CONVERT];

    public function run(array $argv, array $config = []): void
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

    private function runGenerateCommand(string $targetPath, array $config = []): void
    {
        $configObject = $this->getConfig($config);

        $generate = new Generate($configObject);
        $generate->run($targetPath);
    }

    private function runConvertToFppCommand(string $fileToConvert, ?string $outputPath = null, array $config): void
    {
        $fppConvert = new FppConvert($config);
        $fppConvert->run($fileToConvert, $outputPath);
    }

    private function getConfig(array $config): Config {

        if (empty($config)) {
            $config = [];

            $configFile = getcwd() . '/vog_config.json';
            $defaultConfig = json_decode(file_get_contents(__DIR__.'/../DefaultConfig.json'), JSON_OBJECT_AS_ARRAY);
            if ($defaultConfig === null) {
                throw new UnexpectedValueException('Could not parse ' . __DIR__.'/../DefaultConfig.json\n json_last_error_msg(): ' . json_last_error_msg());
            }
            if (file_exists($configFile)) {
                $config = json_decode(file_get_contents($configFile), JSON_OBJECT_AS_ARRAY);
                if ($config === null) {
                    throw new UnexpectedValueException('Could not parse ' . $configFile . '\n json_last_error_msg(): ' . json_last_error_msg());
                }
            }
            $config = array_merge($defaultConfig, $config);
        }

        return Config::fromArray($config);
    }

    private function printUsage(): void
    {
        print("Value Object Generator".PHP_EOL);
        print("generates PHP value objects from a json file.".PHP_EOL);
        print(PHP_EOL);
        print("Usage: ".PHP_EOL);
        print("vendor/bin/vog [command] [path/to/definitionfile.json]".PHP_EOL);
        print(PHP_EOL);
        print("Commands:".PHP_EOL);
        print("\tgenerate".PHP_EOL);
    }
}