<?php

namespace Vog\Test\Unit\Generator;

use Vog\Exception\VogException;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class PhpValueObjectGeneratorTest extends UnitTestCase
{
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
            null
        );
        $generator = new PhpValueObjectClassGenerator(
            $emptyDefinition,
            $this->dummyConfiguration()->getGeneratorOptions(),
            'foo'
        );
        $this->expectException(VogException::class);
        $generator->getCode();
    }
}