<?php


namespace Vog;


class FppConvert
{

    private array $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function run(string $fileToConvert, ?string $outputPath = null)
    {

    }
}