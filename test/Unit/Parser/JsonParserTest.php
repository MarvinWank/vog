<?php

use Unit\UnitTestCase;
use Vog\Parser\JsonParser;

class JsonParserTest extends UnitTestCase
{
    private JsonParser $jsonParser;

    public function setUp(): void
    {
        parent::setUp();

        $this->jsonParser = new JsonParser();
    }

    public function testParseFile()
    {
        $filepath = __DIR__ . "/value.json";

        $definition = $this->jsonParser->parseFile($filepath);
        $values = $definition->FilePathGroup();

        $this->assertEquals(4, $values->count());
        $this->assertEquals('DietStyle', $values->current()->name());
    }
}