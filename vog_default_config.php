<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'src/ConfigOptions.php');
use Vog\ConfigOptions;

return [
    'generatorOptions' => [
        'target' => ConfigOptions::MODE_PSR2,
        'phpVersion' => ConfigOptions::PHP_74,
    ],
];