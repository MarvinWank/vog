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

    public function run(string $fppFilePath, ?string $outputPath = null)
    {
        if (!file_exists($fppFilePath)){
            throw  new UnexpectedValueException("No .fpp file could be found at $fppFilePath");
        }

        $outputPath = $this->getOutputPath($outputPath, $fppFilePath);
        $fileBuilder = new ValueFileBuilder($outputPath);

        $fileContent = file_get_contents($fppFilePath);
        $namespace = $this->parseNamespace($fileContent);
        $fileBuilder->setNamespace($namespace);
        $fileBuilder = $this->parseObjects($fileContent, $fileBuilder);
    }

    private function getOutputPath(?string $givenOutputPath, string $fppFilePath): string
    {
        if ($givenOutputPath){
            if(!file_exists($givenOutputPath)){
                throw new UnexpectedValueException("Given output path $givenOutputPath could not be found");
            }
            return $givenOutputPath;
        }

        $matches = [];
        preg_match('/\/(\w+\.fpp)/', $fppFilePath, $matches);
        if (empty($matches)){
            throw new UnexpectedValueException("File given at $fppFilePath is not a .fpp file");
        }

        $fppFileName = $matches[1];
        $jsonFileName = str_replace(".fpp", "", $fppFileName);
        $jsonFileName .=  ".json";

        return str_replace($fppFileName, $jsonFileName, $fppFilePath);
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
        if ($fileBuilder->getNamespace()){
            array_shift($contentAsArray);
        }

        $vogArtifacts = null;
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