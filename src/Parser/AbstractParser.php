<?php

namespace Vog\Parser;

use Vog\Exception\VogException;
use Vog\ValueObjects\VogDefinition;
use Vog\ValueObjects\VogDefinitionFile;
use Vog\ValueObjects\VogDefinitionSet;
use Vog\ValueObjects\VogTypes;

abstract class AbstractParser
{
    abstract public function parseFile(string $filePath): VogDefinitionFile;

    protected function arrayToDefinition(array $data): VogDefinitionFile
    {
        if (!array_key_exists('root_path', $data)) {
            throw new VogException("Root Path not specified");
        }
        $rootPath = rtrim($data['root_path'], '/');
        unset($data['root_path']);

        $rootNameSpace = null;
        if (array_key_exists('namespace', $data)) {
            $rootNameSpace = rtrim($data['namespace'], '\\');
            unset($data['namespace']);
        }

        $definitions = VogDefinitionSet::fromArray([]);
        foreach ($data as $targetFilepath => $objects) {
            foreach ($objects as $object) {
                $object['directory'] = $targetFilepath . DIRECTORY_SEPARATOR;
                $object['type'] = VogTypes::fromValue($object['type']);
                $definition = VogDefinition::fromArray($object);
                $definitions->add($definition);
            }
        }
        return new VogDefinitionFile($rootPath, $rootNameSpace,$definitions);
    }
}