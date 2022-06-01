<?php

namespace Unit\Factory;

use Vog\Exception\VogException;
use Vog\Factories\ParserFactory;
use Vog\Parser\JsonParser;
use Vog\Test\Unit\UnitTestCase;

class ParserFactoryTest extends UnitTestCase
{
    private ParserFactory $factory;


    public function setUp(): void
    {
        parent::setUp();

        $this->factory = new ParserFactory();
    }

    public function testBuildJsonParser()
    {
        $parser = $this->factory->buildParser('foo.json');
        $this->assertInstanceOf(JsonParser::class, $parser);
    }

    public function testThrowExceptionOnUnsupportedFileFormat()
    {
        $this->expectException(VogException::class);
        $this->factory->buildParser('foo.asdopifä');
    }
}