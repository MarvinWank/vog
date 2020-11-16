<?php


abstract class VogDataOject
{
    protected string $type;
    protected string $name;
    protected string $namespace;
    protected string $target_filepath;

    public function __construct(string $name)
    {
        $this->name = $name;
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
        return $this->target_filepath.DIRECTORY_SEPARATOR.$this->name.".php";
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    protected function getGenericPhpHeader(): string
    {
        return
            <<<EOT
<?php

declare(strict_types=1);

namespace $this->namespace;

final class $this->name
{
EOT;

    }
}