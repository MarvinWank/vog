<?php

namespace Unit\TemplateEngineTest;

use TemplateEngine\TemplateEngine;
use Vog\Test\Unit\UnitTestCase;

class TemplateEngineTest extends UnitTestCase
{

    private TemplateEngine $templateEngine;

    public function setUp(): void
    {
        parent::setUp();

        $this->templateEngine = new TemplateEngine();
    }

    public function testFindAndReplace()
    {
        $replaceMap = [
            'template' => 'funnyTemplateName',
            'anotherVar' => 'that Rhymed!',
        ];
        $expected = "This is the test of a template named funnyTemplateName

because all occurrences should be renamed, its name is mentioned here again: funnyTemplateName

FooBar that Rhymed!

This  is a removed var";

        $result = $this->templateEngine->replaceValues(__DIR__ . '/TestTemplate.vtpl', $replaceMap);

        $this->assertEquals($expected, $result);
    }

}