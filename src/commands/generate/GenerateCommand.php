<?php
declare(strict_types = 1);

namespace Vog\Commands\Generate;

use UnexpectedValueException;

class GenerateCommand extends AbstractCommand
{
    private string $rootPath;
    private string $rootNamespace;

    public function run(string $target): void
    {
        $data = $this->parseFileToJson($target);

        if (!array_key_exists('root_path', $data)) {
            throw new UnexpectedValueException("Root Path not specified");
        }
        $this->rootPath = rtrim($data['root_path'], '/');
        unset($data['root_path']);

        $this->rootNamespace = '';
        if (array_key_exists('namespace', $data)) {
            $this->rootNamespace = rtrim($data['namespace'], '\\');
            unset($data['namespace']);
        }

        foreach ($data as $targetFilepath => $objects) {
            $this->generateMarkerInterfaces($targetFilepath);

            foreach ($objects as $object) {
                $object = $this->buildObject($object, $targetFilepath);
                $success = $this->writeToFile($object);

                if ($success) {
                    echo PHP_EOL . 'Object ' . $object->getName() . ' successfully written to ' . $object->getTargetFilepath();
                }
            }
        }
        echo  PHP_EOL;
    }

    private function buildObject(array $data, string $targetFilepath): AbstractPhpBuilder
    {
        if (!$targetFilepath || $targetFilepath === "") {
            throw new UnexpectedValueException(
                "No namespace was given" . " for object " . $data['name']
            );
        }
        $this->validateObject($data);

        $vog_obj = null;

        switch ($data['type']) {
            case "set":
                $vog_obj = new SetBuilder($data['name'], $this->config);
                break;
            case "enum":
                $vog_obj = new EnumBuilder($data['name'], $this->config);
                break;
            case "nullableEnum":
                $vog_obj = new NullableEnumBuilder($data['name'], $this->config);
                break;
            case "valueObject":
                $vog_obj = new ValueObjectBuilder($data['name'], $this->config);
                break;
            default:
                throw new UnexpectedValueException("Data typ " . $data['type'] . " should be allowed, but is not
                implemented. This is an internal error. Please open an issue on GitHub");
        }

        if (!$vog_obj instanceof SetBuilder) {
            $vog_obj->setValues($data['values']);
        }

        //These Options are only allowed on value objects
        if ($vog_obj instanceof ValueObjectBuilder){
            if (array_key_exists("string_value", $data)) {
                $vog_obj->setStringValue($data['string_value']);
            }
            if (array_key_exists("dateTimeFormat", $data)){
                $vog_obj->setDateTimeFormat($data["dateTimeFormat"]);
            }
        }

        $vog_obj->setTargetFilepath($this->getTargetFilePath($this->rootPath, $targetFilepath));

        $target_namespace = $this->getTargetNamespace($targetFilepath);
        $vog_obj->setNamespace($target_namespace);

        if (array_key_exists('itemType', $data)) {
            $vog_obj->setItemType($data['itemType']);
        }

        if (array_key_exists('extends', $data)) {
            $vog_obj->setExtends($data['extends']);
        }

        if (array_key_exists('implements', $data)) {
            $vog_obj->setImplements($data['implements']);
        }

        if (array_key_exists('final', $data)) {
            $vog_obj->setIsFinal($data['final']);
        }

        if (array_key_exists('mutable', $data)) {
            if ( !($vog_obj instanceof ValueObjectBuilder) && !($vog_obj instanceof SetBuilder)){
                $name = $vog_obj->getName();
                $type = $vog_obj->getType();
                throw new UnexpectedValueException("Mutability is only available on value objects, yet object 
                $name is of type $type");
            }
            $vog_obj->setIsMutable($data['mutable']);
        }

        return $vog_obj;
    }

    private function writeToFile(AbstractPhpBuilder $builderInstance)
    {
        return file_put_contents($builderInstance->getTargetFilepath(), $builderInstance->getPhpCode());
    }

    private function getTargetNamespace(string $targetFilepath): string
    {
        $filePathAsArray = explode(DIRECTORY_SEPARATOR, $targetFilepath);
        array_unshift($filePathAsArray, $this->rootNamespace);

        $filePathAsArray = array_filter($filePathAsArray, static function($pathFragment) {
            if (empty($pathFragment)) {
                return false;
            }

            if ($pathFragment === '.') {
                return false;
            }

            return true;
        });

        array_walk($filePathAsArray, static function(&$pathFragment){
           $pathFragment = ucfirst($pathFragment);
        });
        return implode('\\', array_values($filePathAsArray));
    }



    private function generateMarkerInterfaces(string $targetFilepath): void
    {
        $interfaces = [
            'ValueObject',
            'Enum',
            'Set'
        ];

        foreach ($interfaces as $interface) {
            $object = new InterfaceBuilder($interface, $this->config);
            $object->setTargetFilepath($this->getTargetFilePath($this->rootPath, $targetFilepath));
            $object->setNamespace($this->getTargetNamespace($targetFilepath));
            $success = $this->writeToFile($object);

            if ($success) {
                echo PHP_EOL . 'Object ' . $object->getName() . ' successfully written to ' . $object->getTargetFilepath();
            }
        }
    }
}