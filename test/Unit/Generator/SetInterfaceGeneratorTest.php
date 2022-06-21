<?php

namespace Vog\Test\Integration\Generator;

use Vog\Generator\Php\Interfaces\ValueObjectInterfaceGenerator;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class SetInterfaceGeneratorTest extends UnitTestCase
{
    public function testGenerateSetInterface()
    {
        $definition = new VogDefinition(
            'TestClass',
            'app/DTOs',
            VogTypes::set(),
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

        $generator = new ValueObjectInterfaceGenerator(
            $definition,
            $config->getGeneratorOptions(),
            'Vog\Test\TestObjects',
            __DIR__
        );
        $actual = $generator->getCode();
        $expected = file_get_contents(__DIR__ . '/expected/ValueObjectInterface.vogtest');

        $this->assertEquals($expected, $actual);
    }
}