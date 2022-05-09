<?php

namespace Vog\Test\Unit;

use Vog\Parser\JsonParser;
use Vog\Test\VogTestCase;
use Vog\ValueObjects\VogDefinitionFile;

class UnitTestCase extends VogTestCase
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

}