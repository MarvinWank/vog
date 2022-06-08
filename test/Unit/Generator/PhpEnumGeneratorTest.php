<?php

namespace Vog\Test\Unit\Generator;

use Vog\Generator\Php\Enum\PhpEnumGenerator;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class PhpEnumGeneratorTest extends UnitTestCase
{
    public function testBasicEnumGeneration()
    {
        $definition = new VogDefinition(
            'Diet',
            './expected',
            VogTypes::enum(),
            [
                'Carnivore',
                'Vegetarian',
                'Vegan'
            ],
            null,
            null,
            null,
            null,
            [],
            null,
            null,
        );

        $generator = new PhpEnumGenerator(
            $definition,
            $this->getDummyConfiguration()->getGeneratorOptions(),
            'Vog\Test\TestObjects\Enums',
            __DIR__,
        );
        $code = $generator->getCode();
        $expected = file_get_contents(__DIR__ .'/expected/BasicEnum.php.vogtest');

        $this->assertEquals($expected, $code);
    }
}