<?php


namespace Vog\Commands\Generate;


use InvalidArgumentException;
use UnexpectedValueException;
use Vog\Factories\ParserFactory;
use Vog\ValueObjects\Config;

abstract class AbstractCommand
{
    protected Config $config;
    private const ALL_DATA_TYPES = ['enum', 'nullableEnum', 'valueObject', 'set'];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    abstract public function run(): void;

    protected function parseFile(string $filepath): array
    {
        $factory = new ParserFactory();
        $parser = $factory->buildParser($filepath);

        return $parser->parseFile($filepath);
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