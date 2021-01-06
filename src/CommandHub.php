<?php


namespace Vog;


class CommandHub
{
    private const COMMAND_GENERATE = "generate";
    private const COMMAND_FPP_CONVERT = "fpp_convert";

    private const COMMANDS = [self::COMMAND_GENERATE, self::COMMAND_FPP_CONVERT];

    public function run(array $argv)
    {
        if (!isset($argv[1])) {
            $this->printUsage();
            exit;
        }

        switch ($argv[1]){
            case self::COMMAND_GENERATE: $this->runGenerateCommand($argv[2]);
                break;
            case self::COMMAND_FPP_CONVERT: $this->runConvertToFppCommand($argv[3], $argv[4]);
                break;
            default:
                throw new \UnexpectedValueException("Command $argv is not defined. Defined commands are: "
                . implode(", ", self::COMMANDS));
        }
    }

    private function runGenerateCommand(string $targetPath)
    {
        $generate = new Generate();
        $generate->run($targetPath);
    }

    private function runConvertToFppCommand(string $fileToConvert, ?string $outputPath = null)
    {
        $fppConvert = new FppConvert();
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