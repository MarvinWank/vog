<?php


namespace Vog\Commands\GenerateTypescript;


use Vog\Commands\Generate\AbstractCommand;

class GenerateTypescriptCommand extends AbstractCommand
{
    public function run(string $sourcePath, string $targetPath)
    {
        $json = $this->parseFileToJson($sourcePath);

        // Unnecessary for ts generation
        unset($json['root_path']);
        if (array_key_exists('namespace', $json)) {
            unset($json['namespace']);
        }

        $typescriptCode = "";
        foreach ($json as $namespace => $objects) {
            foreach ($objects as $object) {

                if ($object['type'] !== "valueObject" && $object['type'] !== "set" ) {
                    continue;
                }
                $builder = $this->getBuilder($object);
                $typescriptCode .= $builder->getTypescriptCode();
            }

        }
        $success = $this->writeToFile($typescriptCode, $targetPath);
        if ($success) {
            echo PHP_EOL . 'Typescript successfully written to ' . $targetPath;
        }


    }

    private function getBuilder(array $object): AbstractTypescriptBuilder
    {
        $this->validateObject($object);
        $builder = null;

        switch ($object['type']) {
            case "valueObject":
                $builder = new ValueObjectToTypescriptBuilder($object['name'], $this->config);
                break;
            case "set":
                $builder = new SetToTypescriptBuilder($object['name'], $this->config);
                if (!array_key_exists("itemType", $object)) {
                    $name = $builder->getName();
                    throw new \UnexpectedValueException("Object $name is of type set, but misses itemType definition");
                }
                $builder->setItemType($object['itemType']);
                break;
//            case "enum":
//                break;
//            case "nullableEnum":
//                break;
            default:
//                throw new UnexpectedValueException("Data typ " . $object['type'] . " should be allowed, but is not
//                implemented. This is an internal error. Please open an issue on GitHub");
                break;
        }

        if (!$builder instanceof SetToTypescriptBuilder) {
            $builder->setValues($object['values']);
        }

        return $builder;
    }

    private function writeToFile(string $typescriptCode, string $targetFile)
    {
        return file_put_contents($targetFile, $typescriptCode);
    }
}