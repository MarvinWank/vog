<?php

namespace Unit\Factory;

use Unit\UnitTestCase;
use Vog\Commands\Generate\PhpEnumGenerator;
use Vog\Factories\GeneratorFactory;

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
            $this->dummyConfiguration()->getGeneratorOptions()
        );

        $this->assertInstanceOf(PhpEnumGenerator::class, $enumGenerator);
    }
}