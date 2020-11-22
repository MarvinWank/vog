<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../autoload.php";

use PHPUnit\Framework\TestCase;
use Vog\Vog;

class VogTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $vog = new Vog();
        $vog->run(__DIR__."/testObjects");
    }
}