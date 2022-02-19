<?php

use Vog\Commands\Generate\PhpEnumGenerator;

class PhpEnumGeneratorTest extends \Unit\UnitTestCase
{
    public function testEnumGeneration()
    {
        $generator = new PhpEnumGenerator(
            $this->dummyVogDefinition()->FilePathGroup()[0],
            $this->dummyConfiguration()->getGeneratorOptions()
        );


    }
}