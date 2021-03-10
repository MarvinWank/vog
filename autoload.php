<?php

require __DIR__ . '/src/commands/generate/AbstractBuilder.php';
require __DIR__ . '/src/ValueObjects/Set.php';
require __DIR__ . '/src/ValueObjects/Enum.php';
require __DIR__ . '/src/ValueObjects/ValueObject.php';
require __DIR__ . '/src/ValueObjects/Config.php';
require __DIR__ . '/src/ValueObjects/GeneratorOptions.php';
require __DIR__ . '/src/ValueObjects/TargetMode.php';
require __DIR__ . '/src/commands/generate/ValueObjectBuilder.php';
require __DIR__ . '/src/commands/generate/EnumBuilder.php';
require __DIR__ . "/src/commands/generate/NullableEnumBuilder.php";
require __DIR__ . "/src/commands/generate/InterfaceBuilder.php";
require __DIR__ . '/src/commands/generate/SetBuilder.php';
require __DIR__ . '/src/commands/generate/GenerateCommand.php';
require __DIR__ . '/src/commands/fpp-convert/FppConvertCommand.php';
require __DIR__ . '/src/CommandHub.php';