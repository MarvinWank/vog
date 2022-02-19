<?php

namespace Unit;

use Composer\Util\Tar;
use PHPUnit\Framework\TestCase;
use Vog\Parser\JsonParser;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;
use Vog\ValueObjects\VogDefinitionFile;

class UnitTestCase extends TestCase
{
    private JsonParser $jsonParser;

    public function setUp(): void
    {
        parent::setUp();

        $this->jsonParser = new JsonParser();
    }

    protected function dummyVogDefinition(): VogDefinitionFile
    {
        $filepath = __DIR__ . "/Parser/value.json";
        return $this->jsonParser->parseFile($filepath);
    }

    protected function dummyConfiguration(): Config
    {
        $generatorOptions = new GeneratorOptions(
            TargetMode::MODE_PSR2(),
            null,
            ToArrayMode::DEEP()
        );
        return new Config($generatorOptions);
    }
}