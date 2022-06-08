<?php

namespace Unit\Factory;

use Vog\Factories\GeneratorFactory;
use Vog\Generator\Php\Classes\PhpValueObjectClassGenerator;
use Vog\Generator\Php\Enum\PhpEnumGenerator;
use Vog\Test\Unit\UnitTestCase;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogTypes;

class GeneratorFactoryTest extends UnitTestCase
{
    private GeneratorFactory $generatorFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->generatorFactory = new GeneratorFactory();
    }

    public function testGeneratePhpValueObjectGenerator()
    {
        /** @var VogDefinition $definition */
        $definition = $this->dummyVogDefinition()->FilePathGroup()[0];
        $definition = $definition->with_type(VogTypes::valueObject());

        $enumGenerator = $this->generatorFactory->buildPhpGenerator(
            $definition,
            $this->getDummyConfiguration()->getGeneratorOptions(),
            'Vog\TestObjects',
            __DIR__
        );

        $this->assertInstanceOf(PhpValueObjectClassGenerator::class, $enumGenerator);
    }

    public function testBuildPhpEnumGenerator()
    {
        /** @var VogDefinition $definition */
        $definition = $this->dummyVogDefinition()->FilePathGroup()[0];
        $definition = $definition->with_type(VogTypes::enum());

        $enumGenerator = $this->generatorFactory->buildPhpGenerator(
            $definition,
            $this->getDummyConfiguration()->getGeneratorOptions(),
            'Vog\TestObjects',
            __DIR__
        );

        $this->assertInstanceOf(PhpEnumGenerator::class, $enumGenerator);
    }

    public function testBuildLegacyPhpEnumGenerator()
    {
        if (version_compare(phpversion(), '8.1') >= 0){
            $this->markTestSkipped("Retest with PHP-Version < 8.1!");
        }

        throw new \Exception("To be implemented");
    }

    public function testBuildLegacyPhpNullableEnumGenerator()
    {
        if (version_compare(phpversion(), '8.1') >= 0){
            $this->markTestSkipped("Retest with PHP-Version < 8.1!");
        }

        throw new \Exception("To be implemented");
    }
}