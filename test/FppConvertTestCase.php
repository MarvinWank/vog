<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

use Vog\CommandHub;

class FppConvertTestCase extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test_fpp_convert",
            "fpp_convert",
            __DIR__.DIRECTORY_SEPARATOR."testFppFiles".DIRECTORY_SEPARATOR."dataObjects.fpp"
        ];
        $hub = new CommandHub();
        $hub->run($argv);

        $this->assertFileExists(__DIR__.DIRECTORY_SEPARATOR."testFppFiles".DIRECTORY_SEPARATOR."dataObjects.json");

        $argv = [
            "test",
            "generate",
            __DIR__.DIRECTORY_SEPARATOR."testFppFiles".DIRECTORY_SEPARATOR."dataObjects.json"
        ];
        $hub = new CommandHub();
        $hub->run($argv);
    }
}