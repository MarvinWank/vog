<?php

namespace Vog\Service;

use TemplateEngine\TemplateEngine;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;

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

    public function generateConstructor(array $values): string
    {
        $phpcode = <<<EOT
        
        
            public function __construct (
        EOT;

        foreach ($values as $data_type => $name) {
            $phpcode .= <<<EOT
            
                    $data_type $$name,
            EOT;
        }

        $phpcode = rtrim($phpcode, ',');
        $phpcode .= <<<ETO
        
            ) {
        ETO;
        foreach ($values as $name => $data_type) {
            $name = lcfirst($name);
            $phpcode .= <<<ETO
            
                    \$this->$name = $$name;
            ETO;
        }
        $phpcode .= <<<ETO
        
            }

        ETO;

        return $phpcode;
    }

    public function generateGetters(array $values, GeneratorOptions $generatorOptions): string
    {
        $phpcode = "";
        foreach ($values as $name => $data_type) {
            $functionName = $this->getGetterName($name, $generatorOptions);

            if ($data_type) {
                $phpcode .= <<<ETO
                
                    public function $functionName(): $data_type 
                    {
                ETO;
            } else {
                $phpcode .= <<<ETO
                
                    public function $functionName() 
                    {
                ETO;
            }
            $phpcode .= <<<EOT
            
                    return \$this->$name;
                }
                
            EOT;
        }
        return $phpcode;
    }

    private function getGetterName(string $name, GeneratorOptions $generatorOptions): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($generatorOptions->getTarget())) {
            return 'get' . ucfirst($name);
        }

        return $name;
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