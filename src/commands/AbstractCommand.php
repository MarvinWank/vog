<?php
declare(strict_types = 1);

namespace Vog\Commands\Generate;


use InvalidArgumentException;
use UnexpectedValueException;
use Vog\ValueObjects\Config;

abstract class AbstractCommand
{
    protected Config $config;
    private const ALL_DATA_TYPES = ['enum', 'nullableEnum', 'valueObject', 'set'];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function parseFileToJson(string $filepath): array
    {
        if (!file_exists($filepath)) {
            throw new InvalidArgumentException("File $filepath was not found");
        }
        $file = file_get_contents($filepath);

        $data = json_decode($file, true);
        if (json_last_error_msg() !== "No error") {
            throw new UnexpectedValueException("Could not parse " . $filepath . "\n json_last_error_msg(): " . json_last_error_msg());
        }
        return $data;
    }

    protected function validateObject(array $data)
    {
        if (!array_key_exists("name", $data)) {
            throw new UnexpectedValueException(
                "No name was given for an object"
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
    }

    protected function getTargetFilePath(string $rootPath, $targetFilepath): string
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

        if (!file_exists($rootPath . DIRECTORY_SEPARATOR . $targetFilepath)) {
            throw new UnexpectedValueException("Directory " . $rootPath . DIRECTORY_SEPARATOR . $targetFilepath . " does not exist");
        }

        return rtrim($rootPath . DIRECTORY_SEPARATOR . $targetFilepath, DIRECTORY_SEPARATOR);
    }
}