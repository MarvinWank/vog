<?php

namespace Unit\Factory;

use Vog\Factories\GeneratorFactory;
use Vog\Generator\Php\Classes\PhpEnumClassGenerator;
use Vog\Test\Unit\UnitTestCase;

class GeneratorFactoryTest extends UnitTestCase
{
    private GeneratorFactory $generatorFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->generatorFactory = new GeneratorFactory();
    }

    public function testBuildPhpEnumGenerator()
    {
        $enumGenerator = $this->generatorFactory->buildPhpGenerator(
            $this->dummyVogDefinition()->FilePathGroup()[0],
            $this->getDummyConfiguration()->getGeneratorOptions(),
            'Vog\TestObjects',
            __DIR__
        );

        $this->assertInstanceOf(PhpEnumClassGenerator::class, $enumGenerator);
    }
}