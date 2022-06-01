<?php

namespace Vog\Test\Integration\Generator;

use Integration\IntegrationTestCase;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class PhpValueObjectGeneratorTest extends IntegrationTestCase
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
            null

        );
        $config = $this->getDummyConfiguration();
        $generator = new PhpValueObjectClassGenerator($definition, $config->getGeneratorOptions(), 'Vog\Test\TestObjects');

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
            null
        );
        $config = $this->getDummyConfiguration();
        $generator = new PhpValueObjectClassGenerator($definition, $config->getGeneratorOptions(), 'Vog\Test\TestObjects');

        $phpcode = $generator->getCode();

        $this->assertStringContainsString('final class TestClass extends ParentClass implements FooInterface, BarInterface', $phpcode);
    }
}