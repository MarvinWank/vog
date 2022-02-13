<?php

namespace Vog\Factories;

use Vog\Commands\Generate\AbstractPhpGenerator;
use Vog\Commands\Generate\EnumGenerator;
use Vog\Commands\Generate\NullableEnumGenerator;
use Vog\Commands\Generate\SetGenerator;
use Vog\Commands\Generate\ValueObjectGenerator;
use Vog\Exception\VogException;
use Vog\ValueObjects\VogDefinition;

class GeneratorFactory
{
    public function buildPhpGenerator(VogDefinition $definition): AbstractPhpGenerator
    {
        switch ($definition->type()){

        }
    }
}