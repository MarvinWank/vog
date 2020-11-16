<?php

namespace Vog;

use InvalidArgumentException;
use UnexpectedValueException;

class Vog
{
    private string $root_path;

    private const ALL_DATA_TYPES = ["enum", "nullableEnum"];

    public function run(string $dir, ?string $file = null)
    {
        $data = $this->parse_file($dir, $file);

        if (!array_key_exists("root_path", $data)) {
            throw new UnexpectedValueException("Root Path not specified");
        }
        $this->root_path = $data['root_path'];
        unset($data['root_path']);

        foreach ($data as $namespace => $objects) {
            foreach ($objects as $object) {
                $object = $this->build_vog_data_object($object, $namespace);
                $success = $this->write_to_file($object);

                if($success){
                    echo "\n Object ". $object->getName() . " sucessfully written to ". $object->getTargetFilepath(). " \n";
                }
            }
        }
    }

    private function parse_file(string $dir, ?string $file = null)
    {
        $filepath = $dir . DIRECTORY_SEPARATOR . $file;

        if (!file_exists($dir)) {
            throw new InvalidArgumentException("Directory " . $dir . " does not exist");
        }
        if ($file === null && !file_exists($dir . DIRECTORY_SEPARATOR . 'value.json')) {
            throw new InvalidArgumentException("No 'value.json' was found at " . $dir . 'Please create one or provide a filename');
        }
        if ($file !== null && !file_exists($filepath)) {
            throw new InvalidArgumentException($dir . '/' . $file . " was not found");
        }

        if ($file !== null) {
            $file = file_get_contents($filepath);
        } else {
            $file = file_get_contents($dir . DIRECTORY_SEPARATOR . 'value.json');
        }

        $data = \json_decode($file, true);
        if ($data === null) {
            throw new UnexpectedValueException("Could not parse " . $filepath . "\n json_last_error_msg(): " . json_last_error_msg());
        }
        return $data;
    }

    private function build_vog_data_object(array $data, string $namespace): VogDataObject
    {
        if (!array_key_exists("name", $data)) {
            throw new UnexpectedValueException(
                "No name was given for an object"
            );
        }
        if (!$namespace || $namespace === "") {
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
            default:
                throw new UnexpectedValueException("Data typ " . $data['type'] . " should be allowed, but is not
                implemented. Please open an issue on GitHub");
        }

        $target_filepath = $this->get_target_filepath($namespace);
        $vog_obj->setTargetFilepath($target_filepath);
        $vog_obj->setNamespace($namespace);

        return $vog_obj;
    }

    private function write_to_file(VogDataObject $dataOject)
    {
        $sucess = file_put_contents($dataOject->getTargetFilepath(), $dataOject->getPhpCode());

        return $sucess;
    }

    private function get_target_filepath(string $namespace)
    {
        $filePath = str_replace("\\", DIRECTORY_SEPARATOR, $namespace);

        if (file_exists($filePath)) {
            return $filePath;
        }
        $filePath = strtolower($filePath);
        if (!file_exists($this->root_path . DIRECTORY_SEPARATOR . $filePath)) {
            throw new UnexpectedValueException("Directory " . $this->root_path . DIRECTORY_SEPARATOR . $filePath . " does not exist");
        }

        return $this->root_path . DIRECTORY_SEPARATOR . $filePath;
    }
}