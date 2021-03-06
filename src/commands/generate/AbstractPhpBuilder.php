<?php


namespace Vog\Commands\Generate;


use Vog\ValueObjects\Config;
use Vog\ValueObjects\TargetMode;

abstract class AbstractPhpBuilder extends AbstractBuilder
{
    protected ?string $extends;
    protected array $implements = [];
    protected bool $is_final;
    protected bool $is_mutable;

    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);

        $this->extends = null;
        $this->is_final = false;
        $this->is_mutable = false;
    }

    abstract public function getPhpCode(): string;

    public function setValues(array $values): void
    {
        $psrMode = TargetMode::MODE_PSR2();
        if ($psrMode->equals($this->config->getGeneratorOptions()->getTarget())) {
            $camelized = [];
            foreach ($values as $key => $value) {
                $camelized[self::toCamelCase($key)] = $value;
            }

            $this->values = $camelized;
            return;
        }

        $this->values = $values;
    }

    protected function closeClass($phpcode): string
    {
        $phpcode .= <<<EOT

}
EOT;
        return $phpcode;
    }

    public static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getExtends(): string
    {
        return $this->extends;
    }

    public function setExtends(string $extends): void
    {
        $this->extends = $extends;
    }

    public function getImplements(): array
    {
        return $this->implements;
    }

    public function setImplements(array $implements): void
    {
        // extending classes define default marker interfaces
        $this->implements = array_merge($this->implements, $implements);
    }

    public function isIsFinal(): bool
    {
        return $this->is_final;
    }

    public function setIsFinal(bool $is_final): void
    {
        $this->is_final = $is_final;
    }

    public function isMutable(): bool
    {
        return $this->is_mutable;
    }

    public function setIsMutable(bool $is_mutable): void
    {
        $this->is_mutable = $is_mutable;
    }

    public function getTargetFilepath(): string
    {
        return $this->target_filepath . DIRECTORY_SEPARATOR . ucfirst($this->name) . ".php";
    }

    protected function generateGenericPhpHeader(array $use = self::USE_EXCEPTIONS, string $type = 'class'): string
    {
        $class_statement = $type . ' ' . ucfirst($this->name);
        $useStatement = '';

        if ($this->is_final){
            $class_statement = 'final ' . $class_statement;
        }

        if (!is_null($this->extends)){
            //TODO: if you extend a value object both this class and the parent class implement the marker interface.
            $class_statement .= ' extends '. $this->extends;
        }

        if (!empty($this->implements)){
            $class_statement .= ' implements ' . implode(',', $this->implements);
        }

        foreach ($use as $className) {
            $useStatement .= <<<EOT

            use $className;
            EOT;

        }

        return <<<EOT
<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace $this->namespace;

$useStatement

$class_statement
{
EOT;
    }
}