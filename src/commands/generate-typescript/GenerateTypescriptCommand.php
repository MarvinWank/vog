<?php


namespace Vog\Commands\GenerateTypescript;


use UnexpectedValueException;
use Vog\Commands\Generate\AbstractCommand;
use Vog\Commands\Generate\AbstractPhpBuilder;

class GenerateTypescriptCommand extends AbstractCommand
{
    public function run(string $sourcePath, string $targetPath)
    {
        $this->validatePaths($sourcePath, $targetPath);
        $json = $this->parseFileToJson($sourcePath);

        // Unnecessary for ts generation
        unset($json['root_path']);
        if (array_key_exists('namespace', $json)) {
            unset($json['namespace']);
        }

        foreach ($json as $namespace => $objects) {
            foreach ($objects as $object) {
                $this->buildObject($object);
                $success = $this->writeToFile($object);

                if ($success) {
                    echo PHP_EOL . 'Object ' . $object->getName() . ' successfully written to ' . $object->getTargetFilepath();
                }
            }
        }

    }

    private function validatePaths(string $sourcePath, string $targetPath)
    {
        if (!file_exists($sourcePath)) {
            throw new \UnexpectedValueException("No file was found at $sourcePath");
        }
        if (!is_dir($targetPath)) {
            throw new \UnexpectedValueException("Target path $targetPath is not a directory");
        }
    }

    private function buildObject(array $object): AbstractTypescriptBuilder
    {
        $this->validateObject($object);
        $builder = null;

        switch ($object['type']) {
            case "valueObject":
                $builder = new ValueObjectToTypescriptBuilder($object['name'], $this->config);
                break;
            default:
//                throw new UnexpectedValueException("Data typ " . $object['type'] . " should be allowed, but is not
//                implemented. This is an internal error. Please open an issue on GitHub");
                break;
        }

        $builder->setValues($object['values']);

        return $builder;
    }

    private function writeToFile(AbstractTypescriptBuilder $builderInstance)
    {
        return file_put_contents($builderInstance->getTargetFilepath(), $builderInstance->getTypescriptCode());
    }
}