<?php

use TemplateEngine\TemplateEngine;

class GenericPhpHelper
{
    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';
    protected const USE_EXCEPTIONS = [self::UNEXPECTED_VALUE_EXCEPTION, self::INVALID_ARGUMENT_EXCEPTION];

    public function generateGenericPhpHeader(
        string $name,
        string $namespace,
        array $use = [],
        bool $isFinal = false,
        string $extends = null,
        array $implements = [],
        string $type = 'class'
    ): string
    {
        $classStatement = $type . ' ' . ucfirst($name);
        $useStatement = '';

        if ($isFinal) {
            $classStatement = 'final ' . $classStatement;
        }

        if (!is_null($extends)) {
            //TODO: if you extend a value object both this class and the parent class implement the marker interface.
            $classStatement .= ' extends ' . $extends;
        }

        if (!empty($implements)) {
            $classStatement .= ' implements ' . implode(',', $implements);
        }

        foreach ($use as $className) {
            $useStatement .= <<<EOT

            use $className;
            EOT;
        }

        $templateEngine = new TemplateEngine();
        return $templateEngine->replaceValues(__DIR__ . '/../../templates/GenericPhpFileHeader.vtpl', [
           'namespace' => $namespace,
           'useStatement' => $useStatement,
           'classStatement' => $classStatement
        ]);
    }
}