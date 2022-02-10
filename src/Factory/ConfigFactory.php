<?php

namespace Vog\Factories;

use UnexpectedValueException;
use Vog\ValueObjects\Config;

class ConfigFactory
{
    public function buildConfig(): Config
    {
        return $this->getJsonConfig();
    }

    private function getJsonConfig(): Config
    {
        $config = [];

        $configFile = getcwd() . '/vog_config.json';
        $defaultConfig = json_decode(file_get_contents(__DIR__ . '/../../DefaultConfig.json'), JSON_OBJECT_AS_ARRAY);
        if ($defaultConfig === null) {
            throw new UnexpectedValueException('Could not parse ' . __DIR__ . '/../DefaultConfig.json\n json_last_error_msg(): ' . json_last_error_msg());
        }
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), JSON_OBJECT_AS_ARRAY);
            if ($config === null) {
                throw new UnexpectedValueException('Could not parse ' . $configFile . '\n json_last_error_msg(): ' . json_last_error_msg());
            }
        }
        $generatorOptions = $config['generatorOptions'] ?? [];
        $generatorOptionsDefault = $defaultConfig['generatorOptions'];
        $generatorOptions = array_merge($generatorOptionsDefault, $generatorOptions);
        $config['generatorOptions'] = $generatorOptions;

        $config = array_merge($defaultConfig, $config);

        return Config::fromArray($config);
    }
}