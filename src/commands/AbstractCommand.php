<?php


namespace Vog\Commands\Generate;


use Vog\ValueObjects\Config;

abstract class AbstractCommand
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
}