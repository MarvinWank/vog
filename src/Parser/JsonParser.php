<?php

namespace Vog\Parser;

use Vog\Exception\VogException;
use Vog\ValueObjects\VogDefinitionFile;

class JsonParser extends AbstractParser
{

    /**
     * @throws VogException
     */
    public function parseFile(string $filePath): VogDefinitionFile
    {
        $file = file_get_contents($filePath);

        $data = json_decode($file, true);
        if (json_last_error_msg() !== "No error") {
            throw new VogException("Could not parse " . $filePath . "\n json_last_error_msg(): " . json_last_error_msg());
        }
        return $this->arrayToDefinition($data);
    }
}