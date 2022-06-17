<?php

namespace Unit\Generator;

use Vog\Generator\Php\Enum\PhpLegacyEnumClassGenerator;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class PhpLegacyEnumGeneratorTest extends UnitTestCase
{
    public function testEnumGeneration()
    {
        $definition = new VogDefinition(
            'Diet',
            './expected',
            VogTypes::enum(),
            [
                'Omnivore',
                'Vegetarian',
                'Vegan'
            ],
            null,
            null,
            null,
            null,
            ['Enum'],
            null,
            null,
            null
        );

        $generator = new PhpLegacyEnumClassGenerator(
            $definition,
            $this->getDummyConfiguration()->getGeneratorOptions(),
            'Vog\Test\TestObjects\Enums',
            __DIR__,
        );

        $actual = $generator->getCode();
        $expected = file_get_contents(__DIR__ . '/expected/LegacyEnum.php.vogtest');

        $this->assertEquals($expected, $actual);
    }
}