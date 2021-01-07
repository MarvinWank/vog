<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

use PHPUnit\Framework\TestCase;
use Vog\CommandHub;
use Vog\Generate;

class GenerateTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $argv = [
            "test",
            "generate",
            __DIR__."/testObjects/value.json"
        ];
        $hub = new CommandHub();
        $hub->run($argv);
    }
}