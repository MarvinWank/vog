<?php


namespace Vog\FppConvert;


class ValueFileBuilder
{
    private ObjectsToBuild $objectsToBuild;
    private ?string $namespace;
    private string $targetFilepath;

    public function __construct(string $targetFilepath)
    {
        $this->targetFilepath = $targetFilepath;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getTargetFilepath(): string
    {
        return $this->targetFilepath;
    }

    public function addObject(AbstractJsonObjectBuilder $object)
    {
        $this->objectsToBuild = $this->objectsToBuild->add($object);
    }

}