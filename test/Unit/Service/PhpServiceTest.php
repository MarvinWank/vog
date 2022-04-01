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

    }
}