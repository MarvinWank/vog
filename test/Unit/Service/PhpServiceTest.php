<?php

use Unit\UnitTestCase;
use Vog\Service\PhpService;
use Vog\ValueObjects\TargetMode;

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

    public function testGenerateConstructor()
    {
        $constructorArguments = [
          'FooClass' => 'fooClass',
          'BarClass' => 'barClass'
        ];
        $constructor = $this->genericPhpHelper->generateConstructor($constructorArguments);
        $expected = file_get_contents(__DIR__ . '/SimplePhpConstructor');

        $this->assertEquals($expected, $constructor);
    }

    public function testGenerateGettersPSR2()
    {
        $constructorArguments = [
            'FooClass' => 'fooClass',
            'string' => 'name'
        ];
        $generatorOptions = $this->dummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $getters = $this->genericPhpHelper->generateGetters(
            $constructorArguments,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpPsr2Getters');

        $this->assertEquals($expected, $getters);
    }

    public function testGenerateGettersPSR2NoDataType()
    {
        $constructorArguments = ['fooClass','name'];
        $generatorOptions = $this->dummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $getters = $this->genericPhpHelper->generateGetters(
            $constructorArguments,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpPsr2GettersNoDataType');

        $this->assertEquals($expected, $getters);
    }

    public function testGenerateGettersLegacyMode()
    {
        $constructorArguments = [
            'FooClass' => 'fooClass',
            'string' => 'name'
        ];
        $generatorOptions = $this->dummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_FPP());

        $getters = $this->genericPhpHelper->generateGetters(
            $constructorArguments,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpLegacy2Getters');

        $this->assertEquals($expected, $getters);
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

    public function testGenerateSettersPsr2()
    {
        $values = [
            'FooClass' => 'fooClass',
            'string' => 'name'
        ];

        $generatorOptions = $this->dummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpPsr2Setters');

        $this->assertEquals($expected, $setters);
    }

    public function testGenerateSettersPsr2NoDataType()
    {
        $values = ['fooClass','name'];

        $generatorOptions = $this->dummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpPsr2SettersNoDataType');

        $this->assertEquals($expected, $setters);
    }
}