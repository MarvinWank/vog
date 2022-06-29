<?php

namespace Vog\Service\Php;

use Vog\Service\Service;

abstract class AbstractPhpService extends Service
{
    protected const PHP_PRIMITIVE_TYPES = ["", "string", "?string", "int", "?int", "float", "?float", "bool", "?bool", "array", "?array"];
    protected const TEMPLATE_DIR =  parent::TEMPLATE_DIR . 'Php/';
}