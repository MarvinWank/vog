<?php

namespace Vog\Test\Unit\Generator;

use Vog\Exception\VogException;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class PhpValueObjectGeneratorTest extends UnitTestCase
{
    public function testGenerateValueObject()
    {
        $definition = new VogDefinition(
            'TestClass',
            'app/DTOs',
            VogTypes::valueObject(),
            [
                'title' => 'string',
                'amount' => 'int',
                'active' => 'bool'
            ],
            'title',
            'Y-m-d',
            null,
            null,
            null,
            null,
            null,
            null
        );
        $config = $this->getDummyConfiguration();
        $generator = new PhpValueObjectClassGenerator(
            $definition,
            $config->getGeneratorOptions(),
            'Vog\Test\TestObjects',
            __DIR__
        );

        $phpcode = $generator->getCode();
        $expected = file_get_contents(__DIR__ . '/expected/SimpleValueObject.php.vogtest');
        $this->assertEquals($expected, $phpcode);
    }

    public function testGenerateValueObjectExtendingImplementing()
    {
        $definition = new VogDefinition(
            'TestClass',
            'app/DTOs',
            VogTypes::valueObject(),
            [
                'title' => 'string',
                'amount' => 'int',
                'active' => 'bool'
            ],
            'title',
            'Y-m-d',
            null,
            'ParentClass',
            ['FooInterface', 'BarInterface'],
            true,
            null,
            null
        );
        $config = $this->getDummyConfiguration();
        $generator = new PhpValueObjectClassGenerator(
            $definition,
            $config->getGeneratorOptions(),
            'Vog\Test\TestObjects',
            __DIR__
        );

        $phpcode = $generator->getCode();

        $this->assertStringContainsString('final class TestClass extends ParentClass implements FooInterface, BarInterface', $phpcode);
    }

    public function testGenerateInterface()
    {
        $definition = new VogDefinition(
            'TestClass',
            'app/DTOs',
            VogTypes::valueObject(),
            [
                'title' => 'string',
                'amount' => 'int',
                'active' => 'bool'
            ],
            'title',
            'Y-m-d',
            null,
            'ParentClass',
            null,
            true,
            null,
            null
        );
        $config = $this->getDummyConfiguration();

        $generator = new PhpValueObjectClassGenerator(
            $definition,
            $config->getGeneratorOptions(),
            'Vog\Test\TestObjects',
            __DIR__
        );
        $actual = $generator->getInterfaceGenerator()->getCode();
        $expected = file_get_contents(__DIR__ . '/expected/ValueObjectInterface.vogtest');

        $this->assertEquals($expected, $actual);
    }

    public function testGetValuesThrowsExceptionWhenValuesAreNull()
    {
        $emptyDefinition = new VogDefinition(
            'fooTest',
            'test',
            VogTypes::valueObject(),
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        $generator = new PhpValueObjectClassGenerator(
            $emptyDefinition,
            $this->getDummyConfiguration()->getGeneratorOptions(),
            'foo',
            __DIR__
        );
        $this->expectException(VogException::class);
        $generator->getCode();
    }
}