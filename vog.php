#!/usr/bin/env php
<?php
require __DIR__.'/autoload.php';


use Vog\Vog;

$vog = new Vog();

$dir = $argv[1];
$file = isset($argv[2]) ? $argv[2] : null;

$vog->run($dir, $file);