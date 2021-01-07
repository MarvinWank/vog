<?php


namespace Vog;


use UnexpectedValueException;

class CommandHub
{
    private const COMMAND_GENERATE = "generate";
    private const COMMAND_FPP_CONVERT = "fpp_convert";

    private const COMMANDS = [self::COMMAND_GENERATE, self::COMMAND_FPP_CONVERT];

    public function run(array $argv)
    {
        switch ($argv[1]){
            case self::COMMAND_GENERATE:
                if (!isset($argv[2])){
                    throw new UnexpectedValueException("No path to the value file was provided");
                }
                $this->runGenerateCommand($argv[2]);
            break;
            case self::COMMAND_FPP_CONVERT:
                if (!isset($argv[2])){
                    throw new UnexpectedValueException("No path to the fpp file was provided");
                }
                if (!isset($argv[3])){
                    $argv[3] = null;
                }
                $this->runConvertToFppCommand($argv[2], $argv[3]);
            break;
            default:
                throw new UnexpectedValueException("Command $argv[1] is not defined. Defined commands are: "
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
}