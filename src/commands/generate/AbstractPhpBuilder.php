<?php


namespace Vog\Commands\Generate;


abstract class AbstractPhpBuilder extends AbstractBuilder
{
    abstract public function getPhpCode(): string;
}