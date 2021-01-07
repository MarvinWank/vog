<?php


namespace Vog;


use UnexpectedValueException;

class FppConvert
{
    public function run(string $fileToConvert, ?string $outputPath = null)
    {
        if (!file_exists($fileToConvert)){
            throw  new UnexpectedValueException("No .fpp file could be found at $fileToConvert");
        }

        $fileContent = file_get_contents($fileToConvert);
        $namespace = $this->parseNamespace($fileContent);
        $objects = $this->parseObjects($fileContent, $namespace !== null);
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

    private function parseObjects(string $fileContent, bool $hasNamespace): array
    {
        $contentAsArray = explode(";", $fileContent);
        if ($hasNamespace){
            array_shift($contentAsArray);
        }

        $vogArtifacts = [];
        foreach ($contentAsArray as $fppArtifact){
            $matches = [];
            preg_match('/^\s*data\s+/', $fppArtifact, $matches);
            if (!empty($matches)){
                $vogArtifacts[] = $this->convertToValueObject($fppArtifact);
            }
        }

        return $vogArtifacts;
    }

    private function convertToValueObject(string $fppArtifact): array
    {

    }
}