<?php

namespace Vog\Test\Unit\Generator;


use Vog\Generator\Php\Classes\PhpEnumClassGenerator;
use Vog\Test\Unit\UnitTestCase;

class PhpEnumGeneratorTest extends UnitTestCase
{
    public function testEnumGeneration()
    {
        $generator = new PhpEnumClassGenerator(
            $this->dummyVogDefinition()->FilePathGroup()[0],
            $this->dummyConfiguration()->getGeneratorOptions()
        );


    }
}