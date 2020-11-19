#!/usr/bin/env php
<?php

require __DIR__.'/autoload.php';

use Vog\Vog;

$vog = new Vog();

/**
 * The directory in which the value.json or the optionally specified value file is located
 */
$dir = $argv[1];
/**
 * The optional filename for the value file. If none is given, 'value.json' is assumed
 */
$file = isset($argv[2]) ? $argv[2] : null;

$vog->run($dir, $file);