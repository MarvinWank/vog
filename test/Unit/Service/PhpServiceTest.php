<?php

use Unit\UnitTestCase;
use Vog\Service\PhpService;

class PhpServiceTest extends UnitTestCase
{
    private PhpService $genericPhpHelper;

    public function setUp(): void
    {
        parent::setUp();

        $this->genericPhpHelper = new PhpService();
    }

    public function testGenerateGenericPhpHeaderWithSimpleHeader()
    {
        $phpHeader = $this->genericPhpHelper->generateGenericPhpHeader(
            'testClass',
            'App\Test'
        );
        $expectedHeader = file_get_contents(__DIR__ . '/SimplePhpHeder');

        $this->assertEquals($expectedHeader, $phpHeader);
    }

    public function testGetNamespace()
    {
        $namepsace = $this->genericPhpHelper->getTargetNamespace('TestApp', 'factories');

        $this->assertEquals('TestApp\\Factories', $namepsace);
    }

    public function testGenerateToStringMethod()
    {
        $method = $this->genericPhpHelper->generateToStringMethod('id');
        $expected = file_get_contents(__DIR__ . '/TestToStringMethod');

        $this->assertEquals($expected, $method);
    }

    public function testGenerateValueToArrayMethod()
    {
        $method = $this->genericPhpHelper->generateValueToArrayMethod('Y-m-d');
        $expected = file_get_contents(__DIR__ . '/PhpTestGenerateValueToArray');

        $this->assertEquals($expected, $method);
    }
}