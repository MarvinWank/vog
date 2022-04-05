<?php

namespace Vog\Service;

use TemplateEngine\TemplateEngine;

class PhpService
{
    protected const UNEXPECTED_VALUE_EXCEPTION = 'UnexpectedValueException';
    protected const INVALID_ARGUMENT_EXCEPTION = 'InvalidArgumentException';
    protected const USE_EXCEPTIONS = [self::UNEXPECTED_VALUE_EXCEPTION, self::INVALID_ARGUMENT_EXCEPTION];

    private const TEMPLATE_DIR = __DIR__ . '/../../templates/';

    private TemplateEngine $templateEngine;

    public function __construct()
    {
        $this->templateEngine = new TemplateEngine();
    }

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

        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'GenericPhpFileHeader.vtpl', [
           'namespace' => $namespace,
           'useStatement' => $useStatement,
           'classStatement' => $classStatement
        ]);
    }

    public function getTargetNamespace(string $rootNamespace, string $targetFilepath): string
    {
        $filePathAsArray = explode(DIRECTORY_SEPARATOR, $targetFilepath);
        array_unshift($filePathAsArray, $rootNamespace);

        $filePathAsArray = array_filter($filePathAsArray, static function($pathFragment) {
            if (empty($pathFragment)) {
                return false;
            }

            if ($pathFragment === '.') {
                return false;
            }

            return true;
        });

        array_walk($filePathAsArray, static function(&$pathFragment){
            $pathFragment = ucfirst($pathFragment);
        });
        return implode('\\', array_values($filePathAsArray));
    }

    public function generateToStringMethod(string $stringValue): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'PhpToStringMethod.vtpl', [
            'stringValue' => $stringValue
        ]);
    }

    public function generateValueToArrayMethod(string $dateTimeFormat): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'PhpGenerateValueToArray.vtpl', [
            'dateTimeFormat' => $dateTimeFormat
        ]);
    }
}