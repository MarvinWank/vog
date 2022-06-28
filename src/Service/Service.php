<?php

namespace Vog\Service;

use TemplateEngine\TemplateEngine;

class Service
{
    protected const TEMPLATE_DIR = __DIR__ . '/../../templates/';
    protected TemplateEngine $templateEngine;


    public function __construct()
    {
        $this->templateEngine = new TemplateEngine();
    }
}