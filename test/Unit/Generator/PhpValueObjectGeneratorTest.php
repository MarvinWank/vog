<?php

namespace Vog\Test\Unit\Generator;

use Unit\UnitTestCase;
use Vog\Commands\Generate\PhpValueObjectGenerator;
use Vog\Exception\VogException;
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
        $generator = new PhpValueObjectGenerator(
            $emptyDefinition,
            $this->dummyConfiguration()->getGeneratorOptions(),
            'foo'
        );
        $this->expectException(VogException::class);
        $generator->getPhpCode();
    }
}