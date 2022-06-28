<?php

use Vog\Service\Php\PhpService;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\TargetMode;

class PhpServiceTest extends UnitTestCase
{
    private PhpService $genericPhpHelper;
    private const EXPECTED_DIR = __DIR__ . '/expected/Php';

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
        $expectedHeader = file_get_contents(self::EXPECTED_DIR . '/SimplePhpHeder');

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
          'foo' => 'FooClass',
          'bar' => 'BarClass'
        ];
        $constructor = $this->genericPhpHelper->generateConstructor($constructorArguments);
        $expected = file_get_contents(self::EXPECTED_DIR . '/SimplePhpConstructor');

        $this->assertEquals($expected, $constructor);
    }

    public function testGenerateGettersPSR2()
    {
        $constructorArguments = [
            'foo' => 'FooClass',
            'name' => 'string'
        ];
        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $getters = $this->genericPhpHelper->generateGetters(
            $constructorArguments,
            $generatorOptions
        );
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpPsr2Getters');

        $this->assertEquals($expected, $getters);
    }

    public function testGenerateGettersPSR2NoDataType()
    {
        $this->markTestIncomplete("move to Integration, Generator has to take care of values");

        $constructorArguments = ['foo','name'];
        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $getters = $this->genericPhpHelper->generateGetters(
            $constructorArguments,
            $generatorOptions
        );
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpPsr2GettersNoDataType');

        $this->assertEquals($expected, $getters);
    }

    public function testGenerateGettersLegacyMode()
    {
        $constructorArguments = [
            'foo' => 'FooClass',
            'name' => 'string'
        ];
        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_FPP());

        $getters = $this->genericPhpHelper->generateGetters(
            $constructorArguments,
            $generatorOptions
        );
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpLegacy2Getters');

        $this->assertEquals($expected, $getters);
    }

    public function testGenerateToStringMethod()
    {
        $method = $this->genericPhpHelper->generateToStringMethod('id');
        $expected = file_get_contents(self::EXPECTED_DIR . '/TestToStringMethod');

        $this->assertEquals($expected, $method);
    }

    public function testGenerateValueToArrayMethod()
    {
        $method = $this->genericPhpHelper->generateValueToArrayMethod('Y-m-d');
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpTestGenerateValueToArray');

        $this->assertEquals($expected, $method);
    }

    public function testGenerateSettersPsr2()
    {
        $values = [
            'foo' => 'FooClass',
            'name' => 'string'
        ];

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpPsr2Setters');

        $this->assertEquals($expected, $setters);
    }

    public function testGenerateSettersPsr2NoDataType()
    {
        $this->markTestIncomplete("move to Integration, Generator has to take care of values");

        $values = ['fooClass','name'];

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_PSR2());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpPsr2SettersNoDataType');

        $this->assertEquals($expected, $setters);
    }

    public function testGenerateSettersLegacy()
    {
        $values = [
            'foo' => 'FooClass',
            'name' => 'string'
        ];

        $generatorOptions = $this->getDummyConfiguration()->getGeneratorOptions();
        $generatorOptions = $generatorOptions->withTarget(TargetMode::MODE_FPP());

        $setters = $this->genericPhpHelper->generateSetters(
            $values,
            $generatorOptions
        );
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpLegacySetters');

        $this->assertEquals($expected, $setters);
    }

    public function testGenerateFromArrayWithMethodExistsCheck()
    {
        $values = [
            'root_path' => 'string',
            'namespace' => 'string',
            'filePathGroup' => 'VogDefinitionSet'
        ];

        $method = $this->genericPhpHelper->generateFromArray($values, 'Y-m-d');
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpFromArray');

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
        $expected = file_get_contents(self::EXPECTED_DIR . '/PhpPsr2WithMethods');

        $this->assertEquals($expected, $methods);
    }
}