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
        $actual = $this->setService->generateConstructor([]);
        $expected = file_get_contents(self::EXPECTED_DIR . 'Constructor');

        $this->assertEquals($expected, $actual);
    }
}