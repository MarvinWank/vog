#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

$vog = new Vog();

$dir = $argv[1];
$file = $argv[2];

$vog->run($dir, $file);