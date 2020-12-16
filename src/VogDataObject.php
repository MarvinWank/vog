<?php

namespace Vog;

abstract class VogDataObject
{
    protected string $type;
    protected string $name;
    protected string $namespace;
    protected string $target_filepath;
    protected array $values;

    protected ?string $extends;
    protected array $implements;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->extends = null;
    }

    abstract public function getPhpCode(): string;

    public function getName(): string
    {
        return $this->name;
    }

    public function setTargetFilepath(string $target_filepath)
    {
        $this->target_filepath = $target_filepath;
    }

    public function getTargetFilepath(): string
    {
        return $this->target_filepath . DIRECTORY_SEPARATOR . $this->name . ".php";
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function setValues(array $values)
    {
        $this->values = $values;
    }

    public function getValues(): array
    {
        return $this->values;
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
        $this->implements = $implements;
    }


    protected function generateGenericPhpHeader(): string
    {
        $header = "<?php";
        $header .= "\n\ndeclare(strict_types=1);";
        $header .= "\n\nnamespace $this->namespace;";
        $header .= "\n\nfinal class $this->name";

        if (!is_null($this->extends)){
            $header .= " extends $this->extends";
        }

        if (!empty($this->implements)){
            $header .= " implements ";
            foreach ($this->implements as $index => $interface){
                if ($index < count($this->implements)){
                    $header .= "$interface, ";
                }
                else {
                    $header .= "$interface";
                }
            }
        }

        $header .= "\n{";
        return $header;
    }
}