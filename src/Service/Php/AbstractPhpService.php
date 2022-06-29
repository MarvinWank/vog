<?php

namespace Vog\Service\Php;

use Vog\Service\Service;

abstract class AbstractPhpService extends Service
{
    protected const TEMPLATE_DIR =  parent::TEMPLATE_DIR . 'Php/';
}