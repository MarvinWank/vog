<?php

namespace Unit\Factory;

use Unit\UnitTestCase;
use Vog\Factories\ConfigFactory;

class ConfigFactoryTest extends UnitTestCase
{
    public function testCreateJsonConfig()
    {
        $factory = new ConfigFactory();

        $config = $factory->buildConfig();
    }
}