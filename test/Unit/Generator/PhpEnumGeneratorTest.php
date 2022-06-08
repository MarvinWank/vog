<?php

namespace Vog\Test\Unit\Generator;


use Vog\Generator\Php\Classes\PhpLegacyEnumClassGenerator;
use Vog\Test\Unit\UnitTestCase;

class PhpEnumGeneratorTest extends UnitTestCase
{
    public function testEnumGeneration()
    {
        $generator = new PhpLegacyEnumClassGenerator(
            $this->dummyVogDefinition()->FilePathGroup()[0],
            $this->getDummyConfiguration()->getGeneratorOptions(),
            __DIR__
        );

        throw new \Exception("To be implemented");
    }
}