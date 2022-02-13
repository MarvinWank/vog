<?php

namespace Vog\Factories;

use Vog\Exception\VogException;
use Vog\Parser\JsonParser;
use Vog\Parser\AbstractParser;

class ParserFactory
{
    private const FORMAT_JSON = '.json';

    private const PARSEABLE_FORMATS = [
      self::FORMAT_JSON
    ];

    /**
     * @throws VogException
     */
    public function buildParser(string $filepath): AbstractParser
    {
        if (strpos($filepath, self::FORMAT_JSON)){
            return new JsonParser();
        }

        throw new VogException("File $filepath is not in a parsable format. Parsable formats: " . implode(' ', self::PARSEABLE_FORMATS));
    }

}