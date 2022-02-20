<?php

use Unit\UnitTestCase;

class GenericPhpHelperTest extends UnitTestCase
{
    private GenericPhpHelper $genericPhpHelper;

    public function setUp(): void
    {
        parent::setUp();

        $this->genericPhpHelper = new GenericPhpHelper();
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
}