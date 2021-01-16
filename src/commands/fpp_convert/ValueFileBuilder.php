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

    public function setObjects(ObjectsToBuild $objects)
    {
        $this->objectsToBuild = $objects;
    }

    public function getJsonCode()
    {
        $json = "{";
        if ($this->namespace && $this->namespace !== ""){
            $json .= "\t'namespace': '$this->namespace',";
        }
  $json .= <<<EOT
  "root_path": "$this->targetFilepath",
  ".": [
EOT;
        /** @var AbstractJsonObjectBuilder $element */
        foreach ($this->objectsToBuild->toArray() as $element){
            $json .= "\t\t{";
            $json .= "";
        }

    }

}