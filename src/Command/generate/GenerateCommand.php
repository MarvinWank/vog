<?php

namespace Vog\Commands\Generate;

use InvalidArgumentException;
use UnexpectedValueException;

use Vog\Exception\VogException;
use Vog\Factories\GeneratorFactory;
use Vog\ValueObjects\Config;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogDefinitionFile;
use Vog\ValueObjects\VogDefinitionSet;
use function json_decode;

class GenerateCommand extends AbstractCommand
{
    private GeneratorFactory $generatorFactory;
    private string $targetDir;

    public function __construct(Config $config, string $target)
    {
        parent::__construct($config);

        $this->targetDir = $target;
        $this->generatorFactory = new GeneratorFactory();
    }

    public function run(): void
    {
        $data = $this->parseFile($this->targetDir);

        /** @var VogDefinition $item */
        foreach ($data->FilePathGroup() as $item){
            $generator = $this->generatorFactory->buildPhpGenerator(
                $item,
                $this->config->getGeneratorOptions(),
                $data->namespace()
            );
            $this->writeToFile($generator);
        }
    }

    /**
     * @throws VogException
     */
    private function buildObject(array $data, string $targetFilepath): AbstractPhpGenerator
    {
        if (!$targetFilepath || $targetFilepath === "") {
            throw new VogException(
                "No namespace was given" . " for object " . $data['name']
            );
        }
        $this->validateObject($data);

        $vog_obj = null;

        switch ($data['type']) {
            case "set":
                $vog_obj = new PhpSetGenerator($data['name'], $this->config);
                break;
            case "enum":
                $vog_obj = new PhpEnumGenerator($data['name'], $this->config);
                break;
            case "nullableEnum":
                $vog_obj = new NullablePhpEnumGenerator($data['name'], $this->config);
                break;
            case "valueObject":
                $vog_obj = new PhpValueObjectGenerator($data['name'], $this->config);
                break;
            default:
                throw new UnexpectedValueException("Data typ " . $data['type'] . " should be allowed, but is not
                implemented. This is an internal error. Please open an issue on GitHub");
        }

        if (!$vog_obj instanceof PhpSetGenerator) {
            $vog_obj->setValues($data['values']);
        }

        //These Options are only allowed on value objects
        if ($vog_obj instanceof PhpValueObjectGenerator){
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
            if ( !($vog_obj instanceof PhpValueObjectGenerator) && !($vog_obj instanceof PhpSetGenerator)){
                $name = $vog_obj->getName();
                $type = $vog_obj->getType();
                throw new UnexpectedValueException("Mutability is only available on value objects, yet object 
                $name is of type $type");
            }
            $vog_obj->setIsMutable($data['mutable']);
        }

        return $vog_obj;
    }

    private function writeToFile(AbstractPhpGenerator $builderInstance)
    {
        return file_put_contents($builderInstance->getAbsoluteFilepath(), $builderInstance->getPhpCode());
    }





    private function generateMarkerInterfaces(string $targetFilepath): void
    {
        $interfaces = [
            'ValueObject',
            'Enum',
            'Set'
        ];

        foreach ($interfaces as $interface) {
            $object = new PhpInterfaceGenerator($interface, $this->config);
            $object->setTargetFilepath($this->getTargetFilePath($this->rootPath, $targetFilepath));
            $object->setNamespace($this->getTargetNamespace($targetFilepath));
            $success = $this->writeToFile($object);

            if ($success) {
                echo PHP_EOL . 'Object ' . $object->getName() . ' successfully written to ' . $object->getAbsoluteFilepath();
            }
        }
    }
}