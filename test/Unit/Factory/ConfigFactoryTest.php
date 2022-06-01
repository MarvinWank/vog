<?php

namespace Unit\Factory;

use Vog\Factories\ConfigFactory;
use Vog\Test\Unit\UnitTestCase;

class ConfigFactoryTest extends UnitTestCase
{
    private ConfigFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->factory = new ConfigFactory();
    }

    public function testCreateJsonConfigFromDefaultConfig()
    {
        $config = $this->factory->buildConfig();

        $this->assertCount(1, $config->toArray());
        $this->assertCount(3, $config->getGeneratorOptions()->toArray());
        $this->assertEquals('MODE_PSR2', $config->getGeneratorOptions()->getTarget());
        $this->assertEquals('Y-m-d', $config->getGeneratorOptions()->getDateTimeFormat());
        $this->assertEquals('DEEP', $config->getGeneratorOptions()->getToArrayMode());
    }

    public function testCreateJsonConfigFromCustomConfig()
    {
        file_put_contents('vog_config.json', '
             {
                "generatorOptions": {
                    "target": "MODE_FPP",
                    "dateTimeFormat": "d.m.Y",
                    "toArrayMode": "SHALLOW"
                }
             }
        ');

        $config = $this->factory->buildConfig();

        $this->assertCount(1, $config->toArray());
        $this->assertCount(3, $config->getGeneratorOptions()->toArray());
        $this->assertEquals('MODE_FPP', $config->getGeneratorOptions()->getTarget());
        $this->assertEquals('d.m.Y', $config->getGeneratorOptions()->getDateTimeFormat());
        $this->assertEquals('SHALLOW', $config->getGeneratorOptions()->getToArrayMode());

        unlink('vog_config.json');
    }

    public function testCreateJsonConfigFromIncompleteCustomConfig()
    {
        file_put_contents('vog_config.json', '
             {
                "generatorOptions": {
                    "dateTimeFormat": "d.m.Y"
                }
             }
        ');

        $config = $this->factory->buildConfig();

        $this->assertCount(1, $config->toArray());
        $this->assertCount(3, $config->getGeneratorOptions()->toArray());
        $this->assertEquals('MODE_PSR2', $config->getGeneratorOptions()->getTarget());
        $this->assertEquals('d.m.Y', $config->getGeneratorOptions()->getDateTimeFormat());
        $this->assertEquals('DEEP', $config->getGeneratorOptions()->getToArrayMode());

        unlink('vog_config.json');
    }
}