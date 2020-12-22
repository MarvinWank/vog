#!/usr/bin/env php
<?php

use Vog\CommandHub;

require __DIR__.'/autoload.php';


$hub = new CommandHub();
$hub->run($argv);