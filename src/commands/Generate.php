<?php

namespace Vog;

use InvalidArgumentException;
use UnexpectedValueException;

class Generate
{
    private string $rootPath;

    private const ALL_DATA_TYPES = ["enum", "nullableEnum", "valueObject"];

    public function run(string $target)
    {
        $data = $this->parseFile($target);

        if (!array_key_exists("root_path", $data)) {
            throw new UnexpectedValueException("Root Path not specified");
        }
        $this->rootPath = $data['root_path'];
        unset($data['root_path']);

        foreach ($data as $targetFilepath => $objects) {
            foreach ($objects as $object) {
                $object = $this->buildVogDataObject($object, $targetFilepath);
                $success = $this->writeToFile($object);

                if ($success) {
                    echo "Object " . $object->getName() . " sucessfully written to " . $object->getTargetFilepath() . " \n";
                }
            }
        }
    }

    private function parseFile(string $filepath)
    {
        if (!file_exists($filepath)) {
            throw new InvalidArgumentException("File $filepath was not found");
        }
        $file = file_get_contents($filepath);

        $data = \json_decode($file, true);
        if ($data === null) {
            throw new UnexpectedValueException("Could not parse " . $filepath . "\n json_last_error_msg(): " . json_last_error_msg());
        }
        return $data;
    }

    private function buildVogDataObject(array $data, string $targetFilepath): VogDataObject
    {
        if (!array_key_exists("name", $data)) {
            throw new UnexpectedValueException(
                "No name was given for an object"
            );
        }
        if (!$targetFilepath || $targetFilepath === "") {
            throw new UnexpectedValueException(
                "No namespace was given" . " for object " . $data['name']
            );
        }
        if (!array_key_exists("type", $data)) {
            throw new UnexpectedValueException(
                "Value Object type not specified. Allowed types: " . implode(", ", self::ALL_DATA_TYPES)
                . " for object " . $data['name']
            );
        }
        if (!in_array($data['type'], self::ALL_DATA_TYPES)) {
            throw new UnexpectedValueException(
                "Unknow value object type '" . $data['type'] . "' Allowed types: " . implode(", ", self::ALL_DATA_TYPES)
                . " for object " . $data['name']
            );
        }
        if (!array_key_exists("values", $data)) {
            throw new UnexpectedValueException(
                "No values were given" . " for object " . $data['name']
            );
        }

        $vog_obj = null;

        switch ($data['type']) {
            case "enum":
                $vog_obj = new Enum($data['name']);
                $vog_obj->setValues($data['values']);
                break;
            case "nullableEnum":
                $vog_obj = new NullableEnum($data['name']);
                $vog_obj->setValues($data['values']);
                break;
            case "valueObject":
                $vog_obj = new ValueObject($data['name']);
                $vog_obj->setValues($data['values']);
                if (array_key_exists("string_value", $data)) {
                    $vog_obj->set_string_value($data['string_value']);
                }
                break;
            default:
                throw new UnexpectedValueException("Data typ " . $data['type'] . " should be allowed, but is not
                implemented. Please open an issue on GitHub");
        }

        $target_namespacee = $this->getTargetNamespace($targetFilepath);
        $vog_obj->setNamespace($target_namespacee);
        $vog_obj->setTargetFilepath($this->rootPath . DIRECTORY_SEPARATOR . $targetFilepath);

        if (array_key_exists("extends", $data)) {
            $vog_obj->setExtends($data['extends']);
        }
        if (array_key_exists("implements", $data)) {
            $vog_obj->setImplements($data['implements']);
        }
        if (array_key_exists("final", $data)) {
            $vog_obj->setIsFinal($data['final']);
        }

        return $vog_obj;
    }

    private function writeToFile(VogDataObject $dataOject)
    {
        $sucess = file_put_contents($dataOject->getTargetFilepath(), $dataOject->getPhpCode());

        return $sucess;
    }

    private function getTargetNamespace(string $targetFilepath)
    {
        if (!file_exists($this->rootPath . DIRECTORY_SEPARATOR . $targetFilepath)) {
            throw new UnexpectedValueException("Directory " . $targetFilepath . " does not exist");
        }


        $filePathAsArray = explode(DIRECTORY_SEPARATOR, $targetFilepath);
        foreach ($filePathAsArray as $key => $path) {
            $filePathAsArray[$key] = ucfirst($path);
        }
        $targetFilepath = implode(DIRECTORY_SEPARATOR, $filePathAsArray);
        $namespace = str_replace(DIRECTORY_SEPARATOR, '\\', $targetFilepath);

        return $namespace;
    }
}