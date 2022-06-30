<?php

namespace Vog\Test\Unit\Service;

use Vog\Service\Php\SetService;
use Vog\Test\Unit\UnitTestCase;

class PhpSetServiceTest extends UnitTestCase
{
    private SetService $setService;
    private const EXPECTED_DIR = __DIR__ . '/expected/Php/Set/';

    public function setUp(): void
    {
        parent::setUp();

        $this->setService = new SetService();
    }

    public function testGenerateConstructor()
    {
        $actual = $this->setService->generateConstructor();
        $expected = file_get_contents(self::EXPECTED_DIR . 'Constructor.vtpl');

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateToArrayWithNonPrimitiveType()
    {
        $actual = $this->setService->generateToArrayNonPrimitive();
        $excpected = file_get_contents(self::EXPECTED_DIR . 'ToArrayNonPrimitive.vtpl');

        $this->assertEquals($excpected, $actual);
    }

    public function testGenerateToArrayWithPrimitiveType()
    {
        $actual = $this->setService->generateToArrayPrimitive();
        $excpected = file_get_contents(self::EXPECTED_DIR . 'ToArrayPrimitive.vtpl');

        $this->assertEquals($excpected, $actual);
    }
    
    public function testGenerateFromArrayForUnspecifiedType()
    {
        $actual = $this->setService->generateFromArrayForUnspecifiedType();
        $expected = file_get_contents(self::EXPECTED_DIR . 'FromArrayForUnspecifiedType.vtpl');

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateFromArrayForPrimitiveType()
    {
        $actual = $this->setService->generateFromArrayForNonPrimitiveType('FooClass', 'FooSet');
        $expected = file_get_contents(self::EXPECTED_DIR . 'FromArrayForNonPrimitiveType.vtpl');

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateGenericFunctions()
    {
        $actual = $this->setService->generateGenericFunctions('FooClass');
        $expected = file_get_contents(self::EXPECTED_DIR . 'GenericFunctions.vtpl');

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateAddFunction()
    {
        $actual = $this->setService->generateAddFunction('FooClass');
        $expected = file_get_contents(self::EXPECTED_DIR . 'AddFunction.vtpl');

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateRemoveForNonPrimitiveType()
    {
        $actual = $this->setService->generateRemoveForNonPrimitiveType();
        $expected = file_get_contents(self::EXPECTED_DIR . 'RemoveFunctionNonPrimitive.vtpl');

        $this->assertEquals($actual, $expected);
    }

    public function testGenerateRemoveForPrimitiveType()
    {
        $actual = $this->setService->generateRemoveForNonPrimitiveType();
        $expected = file_get_contents(self::EXPECTED_DIR . 'RemoveFunctionNonPrimitive.vtpl');

        $this->assertEquals($actual, $expected);
    }

    public function testGenerateMutableAddFunction()
    {
        $actual = $this->setService->generateMutableAddFunction('FooClass');
        $expected = file_get_contents(self::EXPECTED_DIR . 'MutableAddFunction.vtpl');

        $this->assertEquals($expected, $actual);
    }


}