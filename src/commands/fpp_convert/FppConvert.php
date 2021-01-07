<?php


namespace Vog\FppConvert;


use UnexpectedValueException;

class FppConvert
{

    private ?array $config;

    public function __construct(?array $config)
    {
        $this->config = $config;
    }

    public function run(string $fileToConvert, ?string $outputPath = null)
    {
        if (!file_exists($fileToConvert)){
            throw  new UnexpectedValueException("No .fpp file could be found at $fileToConvert");
        }


        $fileContent = file_get_contents($fileToConvert);
        $namespace = $this->parseNamespace($fileContent);
        $fileBuilder = new ValueFileBuilder();
        $fileBuilder = $this->parseObjects($fileContent, $fileBuilder);
    }

    private function getOutPutPath()
    {

    }

    private function parseNamespace(string $fileContent): ?string
    {
        $matches = [];
        preg_match('/namespace[\s]+(\w+);/', $fileContent, $matches);

        if (empty($matches)){
            return null;
        }
        else{
            return $matches[1];
        }
    }

    private function parseObjects(string $fileContent, ValueFileBuilder $fileBuilder): array
    {
        $contentAsArray = explode(";", $fileContent);
        if ($namespace){
            array_shift($contentAsArray);
        }

        $vogArtifacts = [];
        foreach ($contentAsArray as $fppArtifact){
            $object = null;
            $matches = [];
            preg_match('/^\s*data\s+/', $fppArtifact, $matches);
            if (!empty($matches)){
                $object = $this->convertToValueObject($fppArtifact);
            }
        }

        return $vogArtifacts;
    }

    private function convertToValueObject(string $fppArtifact): AbstractJsonObjectBuilder
    {

    }

    private function getNamespaceForObject(): string
    {

    }
}