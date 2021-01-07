<?php
require('src/ConfigOptions.php');
use Vog\ConfigOptions;

return [
    'generatorOptions' => [
        'target' => ConfigOptions::MODE_PSR2,
        'phpVersion' => ConfigOptions::PHP_74,
    ],
];