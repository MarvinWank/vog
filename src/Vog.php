<?php

namespace Vog;

use Vog\Factories\CommandFactory;
use Vog\ValueObjects\Config;

class Vog
{
    public function run(array $argv, Config $config = null): void
    {
        if (!isset($argv[1])) {
            $this->printUsage();
            exit;
        }

        $commandFactory = new CommandFactory();

        $command = $commandFactory->buildCommand($argv[1], $config, $argv);
        $command->run();
    }

    private function printUsage(): void
    {
        print("Value Object Generator" . PHP_EOL);
        print("generates PHP value objects from a json file." . PHP_EOL);
        print(PHP_EOL);
        print("Usage: " . PHP_EOL);
        print("vendor/bin/vog [command] [path/to/definitionfile.json]" . PHP_EOL);
        print(PHP_EOL);
        print("Commands:" . PHP_EOL);
        print("\tgenerate" . PHP_EOL);
    }
}