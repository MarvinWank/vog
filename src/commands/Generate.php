<?php

namespace Vog;

use InvalidArgumentException;
use UnexpectedValueException;

use Vog\ValueObjects\Config;
use function json_decode;

class Generate
{
    private Config $config;
    private string $rootPath;
    private string $rootNamespace;

    private const ALL_DATA_TYPES = ['enum', 'nullableEnum', 'valueObject', 'set'];

    public function __construct(Config $config) {
        $this->config = $config;
    }

    public function run(string $target): void
    {
        $data = $this->parseFile($target);

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

    private function parseFile(string $filepath)
    {
        if (!file_exists($filepath)) {
            throw new InvalidArgumentException("File $filepath was not found");
        }
        $file = file_get_contents($filepath);

        $data = json_decode($file, true);
        if ($data === null) {
            throw new UnexpectedValueException("Could not parse " . $filepath . "\n json_last_error_msg(): " . json_last_error_msg());
        }
        return $data;
    }

    private function buildObject(array $data, string $targetFilepath): AbstractBuilder
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
                "Unknown value object type '" . $data['type'] . "' Allowed types: " . implode(", ", self::ALL_DATA_TYPES)
                . " for object " . $data['name']
            );
        }
        if ($data['type'] !== 'set' && !array_key_exists("values", $data)) {
            throw new UnexpectedValueException(
                "No values were given" . " for object " . $data['name']
            );
        }

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
                implemented. Please open an issue on GitHub");
        }

        if (!$vog_obj instanceof SetBuilder) {
            $vog_obj->setValues($data['values']);
        }

        if (array_key_exists("string_value", $data)) {
            $vog_obj->setStringValue($data['string_value']);
        }

        $vog_obj->setTargetFilepath($this->getTargetFilePath($targetFilepath));

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
            if ( !($vog_obj instanceof ValueObjectBuilder)){
                $name = $vog_obj->getName();
                $type = $vog_obj->getType();
                throw new UnexpectedValueException("Mutability is only available on value objects, yet object 
                $name is of type $type");
            }
            $vog_obj->setIsMutable($data['mutable']);
        }

        return $vog_obj;
    }

    private function writeToFile(AbstractBuilder $builderInstance)
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

    private function getTargetFilePath(string $targetFilepath): string
    {
        $filePathAsArray = explode(DIRECTORY_SEPARATOR, $targetFilepath);
        $filePathAsArray = array_filter($filePathAsArray, static function($pathFragment) {
            if (empty($pathFragment)) {
                return false;
            }

            if ($pathFragment === '.') {
                return false;
            }

            return true;
        });

        $targetFilepath = implode(DIRECTORY_SEPARATOR, array_values($filePathAsArray));

        if (!file_exists($this->rootPath . DIRECTORY_SEPARATOR . $targetFilepath)) {
            throw new UnexpectedValueException("Directory " . $this->rootPath . DIRECTORY_SEPARATOR . $targetFilepath . " does not exist");
        }

        return rtrim($this->rootPath . DIRECTORY_SEPARATOR . $targetFilepath, DIRECTORY_SEPARATOR);
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
            $object->setTargetFilepath($this->getTargetFilePath($targetFilepath));
            $object->setNamespace($this->getTargetNamespace($targetFilepath));
            $success = $this->writeToFile($object);

            if ($success) {
                echo PHP_EOL . 'Object ' . $object->getName() . ' successfully written to ' . $object->getTargetFilepath();
            }
        }
    }
}