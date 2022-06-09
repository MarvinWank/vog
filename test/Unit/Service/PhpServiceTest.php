<?php

use Vog\Service\PhpService;
use Vog\Test\Unit\UnitTestCase;
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
        $phpHeader = $this->genericPhpHelper->generatePhpClassHeader(
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
        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
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
        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
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
        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
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

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
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

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpPsr2SettersNoDataType');

        $this->assertEquals($expected, $setters);
    }

    public function testGenerateSettersLegacy()
    {
        $values = [
            'FooClass' => 'fooClass',
            'string' => 'name'
        ];

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_FPP());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(__DIR__ . '/PhpLegacySetters');

        $this->assertEquals($expected, $setters);
    }

    public function testGenerateFromArray()
    {
        $values = [
            'root_path' => 'string',
            'namespace' => 'string',
            'filePathGroup' => 'VogDefinitionSet'
        ];

        $method = $this->genericPhpHelper->generateFromArray($values, 'Y-m-d');
        $expected = file_get_contents(__DIR__ . '/PhpFromArray');

        $this->assertEquals($expected, $method);
    }

    public function testGenerateFromArrayWithNullableValues()
    {
        $values = [
            'root_path' => '?string',
            'namespace' => '?string',
            'filePathGroup' => '?VogDefinitionSet'
        ];

        $method = $this->genericPhpHelper->generateFromArray($values, 'Y-m-d');

        $this->assertStringNotContainsString("if (!array_key_exists('root_path', \$array))", $method);
        $this->assertStringNotContainsString("if (!array_key_exists('namespace', \$array))", $method);
        $this->assertStringNotContainsString("if (!array_key_exists('filePathGroup', \$array))", $method);
    }

    public function testGenerateWithMethodsPsr2()
    {
        $values = [
            'fooClass' => 'FooClass',
            'name' => 'string'
        ];

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $methods = $this->genericPhpHelper->generateWithMethods($values, $generatorOptions);
        $expected = file_get_contents(__DIR__ . '/PhpPsr2WithMethods');

        $this->assertEquals($expected, $methods);
    }
}