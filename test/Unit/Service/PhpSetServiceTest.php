<?php

namespace Vog\Test\Unit\Service;

use Vog\Service\Php\SetService;
use Vog\Test\Unit\UnitTestCase;

class PhpSetServiceTest extends UnitTestCase
{
    private SetService $setService;
    private const EXPECTED_DIR = __DIR__ . '/expected/Php/Set/';

    public function setUp(): void
    {
        parent::setUp();

        $this->setService = new SetService();
    }

    public function testGenerateConstructor()
    {
        $actual = $this->setService->generateConstructor();
        $expected = file_get_contents(self::EXPECTED_DIR . 'Constructor.vtpl');

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateToArrayWithNonPrimitiveType()
    {
        $actual = $this->setService->generateToArray('fooClass');
        $excpected = file_get_contents(self::EXPECTED_DIR . 'ToArrayNonPrimitive.vtpl');

        $this->assertEquals($excpected, $actual);
    }

    public function testGenerateToArrayWithPrimitiveType()
    {
        $actual = $this->setService->generateToArray('int');
        $excpected = file_get_contents(self::EXPECTED_DIR . 'ToArrayPrimitive.vtpl');

        $this->assertEquals($excpected, $actual);
    }

}