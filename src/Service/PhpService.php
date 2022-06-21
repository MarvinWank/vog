<?php

namespace Vog\Service;

use TemplateEngine\TemplateEngine;
use Vog\ValueObjects\GeneratorOptions;
use Vog\ValueObjects\TargetMode;
use Vog\ValueObjects\ToArrayMode;

class PhpService
{
    private TemplateEngine $templateEngine;

    private const PHP_PRIMITIVE_TYPES = ["", "string", "?string", "int", "?int", "float", "?float", "bool", "?bool", "array", "?array"];
    private const TEMPLATE_DIR = __DIR__ . '/../../templates/';

    public function __construct()
    {
        $this->templateEngine = new TemplateEngine();
    }

    public function generatePhpClassHeader(
        string $name,
        string $namespace,
        array  $use = [],
        bool   $isFinal = false,
        string $extends = null,
        array  $implements = [],
        string $type = 'class'
    ): string
    {
        return $this->generatePhpHeader($name, $namespace, $use, $isFinal, $extends, $implements, $type);
    }

    public function generatePhpHeader(
        string  $name,
        string  $namespace,
        array   $use = [],
        bool    $isFinal = false,
        string  $extends = null,
        array   $implements = [],
        string  $type = 'class',
        ?string $enumType = null
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
            $classStatement .= ' implements ' . implode(', ', $implements);
        }

        if ($enumType) {
            $classStatement .= ": " . $enumType;
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

        $filePathAsArray = array_filter($filePathAsArray, static function ($pathFragment) {
            if (empty($pathFragment)) {
                return false;
            }

            if ($pathFragment === '.') {
                return false;
            }

            return true;
        });

        array_walk($filePathAsArray, static function (&$pathFragment) {
            $pathFragment = ucfirst($pathFragment);
        });
        return implode('\\', array_values($filePathAsArray));
    }

    public function generateConstructor(array $values): string
    {
        $phpcode = <<<EOT
        
        
            public function __construct(
        EOT;

        foreach ($values as $name => $data_type) {
            $phpcode .= <<<EOT
            
                    $data_type $$name,
            EOT;
        }

        $phpcode = rtrim($phpcode, ',');
        $phpcode .= <<<ETO
        
            )
            {
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

    //TODO: Refactor into two methods
    public function generateGetters(array $values, GeneratorOptions $generatorOptions): string
    {
        $phpcode = "";
        foreach ($values as $name => $dataType) {
            $functionName = $this->getGetterName($name, $generatorOptions);

            if (is_numeric($dataType)) {
                $dataType = null;
            }

            if ($dataType) {
                $phpcode .= <<<ETO
                
                    public function $functionName(): $dataType
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

    //TODO: Refactor into two methods
    public function generateSetters(array $values, GeneratorOptions $generatorOptions): string
    {
        $phpcode = "";
        foreach ($values as $dataType => $name) {
            $functionName = $this->getSetter($name, $generatorOptions);

            if (is_numeric($dataType)) {
                $dataType = null;
            }

            if ($dataType) {
                $phpcode .= <<<EOT
                
                    public function $functionName($dataType $$name): void
                    {
                EOT;
            } else {
                $phpcode .= <<<EOT
                
                    public function $functionName($$name): void
                    {
                EOT;
            }
            $phpcode .= <<<EOT
            
                    \$this->$name = $$name;
                }

            EOT;
        }
        return $phpcode;
    }

    private function getSetter(string $name, GeneratorOptions $generatorOptions): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($generatorOptions->getTarget())) {
            return 'set' . ucfirst($name);
        }

        return 'set_' . $name;
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

    public function generateFromArray(array $values, string $dateTimeFormat): string
    {
        $phpcode = '

    public static function fromArray(array $array): self
    {';

        foreach ($values as $name => $datatype) {
            if (!$this->isDataTypeNullable($datatype)) {
                $phpcode .= <<<EOT
                
                        if (!array_key_exists('$name', \$array)) {
                            throw new UnexpectedValueException('Array key $name does not exist');
                        }
                EOT;
            }

            $datatype = $this->sanitizeNullableDatatype($datatype);
            if ($datatype === "\\DateTime" || $datatype === "\\DateTimeImmutable") {
                $phpcode .= $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'PHpFromArrayDateTime.vtpl', [
                    'name' => $name,
                    'format' => $dateTimeFormat,
                    'dateObject' => $datatype
                ]);
            } elseif (!$this->isPrimitivePhpType($datatype)) {
                $phpcode .= $this->templateEngine->replaceValues(self::TEMPLATE_DIR . 'PhpFromArrayForVog.vtpl', [
                    'name' => $name,
                    'dataType' => $datatype
                ]);
            }
        }

        $phpcode .= <<<EOT
        
        
                return new self(
        EOT;

        foreach ($values as $name => $datatype) {
            $phpcode .= <<<EOT
            
                        \$array['$name'] ?? null,
            EOT;
        }

        $phpcode = rtrim($phpcode, ',');
        $phpcode .= <<<EOT
        
                );
            }
        
        EOT;

        return $phpcode;
    }

    public function generateToArray(array $values, GeneratorOptions $generatorOptions)
    {
        $phpcode = <<<EOT
        
            public function toArray(): array
            {
                return [
        EOT;

        if ($generatorOptions->getToArrayMode()->equals(ToArrayMode::DEEP())) {

            foreach ($values as $name => $datatype) {
                if (!$this->isPrimitivePhpType($datatype)) {
                    $phpcode .= <<<EOT
                    
                                '$name' =>  \$this->valueToArray(\$this->$name),
                    EOT;
                } else {
                    $phpcode .= <<<EOT
                    
                                '$name' => \$this->$name,
                    EOT;
                }
            }

        } elseif ($generatorOptions->getToArrayMode()->equals(ToArrayMode::SHALLOW())) {

            foreach ($values as $name => $datatype) {
                $phpcode .= <<<EOT
                
                            '$name' => \$this->$name,
                EOT;
            }
        } else {
            throw new \UnexpectedValueException("Unexpected Config value for toArrayMode");
        }

        $phpcode .= "
        ];
    }";

        return $phpcode;
    }

    public function generateWithMethods(array $values, GeneratorOptions $generatorOptions): string
    {
        $phpcode = "";
        foreach ($values as $name => $data_type) {
            $functionName = $this->getWithFunctionName($name, $generatorOptions);
            $phpcode .= <<<EOT
            
                public function $functionName($data_type $$name): self
                {
                    return new self(
            EOT;

            foreach ($values as $name_assigner => $data_type_assginer) {
                if ($name_assigner === $name) {
                    $phpcode .= <<<EOT
                    
                                $$name_assigner,
                    EOT;

                    continue;
                }
                $phpcode .= <<<EOT
                
                            \$this->$name_assigner,
                EOT;
            }
            $phpcode = rtrim($phpcode, ',');
            $phpcode .= <<<EOT

                    );
                }
            
            EOT;
        }

        return $phpcode;
    }

    public function getInterfaceMethods(): string
    {
        return $this->templateEngine->replaceValues(
            self::TEMPLATE_DIR . '/PhpValueObjectInterfaceMethods.vtpl',
            []
        );
    }

    private function getWithFunctionName(string $name, GeneratorOptions $generatorOptions): string
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($generatorOptions->getTarget())) {
            return 'with' . ucfirst($name);
        }

        return 'with_' . $name;
    }

    private function isDataTypeNullable(string $datatype): bool
    {
        if (strpos($datatype, '?') !== false) {
            return true;
        }

        return false;
    }

    private function sanitizeNullableDatatype(string $datatype): string
    {
        return str_replace('?', '', $datatype);
    }

    private function isPrimitivePhpType(string $type): bool
    {
        return in_array($type, self::PHP_PRIMITIVE_TYPES);
    }
}