<?php

namespace Vog\Test\Integration\Generator;

use Integration\IntegrationTestCase;
use Vog\Generator\Php\Interfaces\ValueObjectInterfaceGenerator;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class ValueObjectInterfaceGeneratorTest extends IntegrationTestCase
{
    public function testGenerateValueObjectInterface()
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
            null
        );
        $config = $this->getDummyConfiguration();

        $generator = new ValueObjectInterfaceGenerator($definition, $config->getGeneratorOptions(), 'Vog\Test\TestObjects');
        $actual = $generator->getCode();
        $expected = file_get_contents(__DIR__ . '/expected/ValueObjectInterface.vogtest');

        $this->assertEquals($expected, $actual);
    }
}