<?php
declare(strict_types = 1);

namespace Vog;


class FppConvertCommand
{

    private array $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function run(string $fileToConvert, ?string $outputPath = null)
    {

    }
}