<?php

require_once __DIR__ . '/src/commands/AbstractBuilder.php';
require_once __DIR__ . '/src/commands/AbstractCommand.php';
require_once __DIR__ . '/src/commands/generate/AbstractPhpBuilder.php';
require_once __DIR__ . '/src/commands/generate-typescript/AbstractTypescriptBuilder.php';
require_once __DIR__ . '/src/commands/generate-typescript/ValueObjectToTypescriptBuilder.php';
require_once __DIR__ . '/src/commands/generate-typescript/SetToTypescriptBuilder.php';
require_once __DIR__ . '/src/commands/generate-typescript/EnumToTypescriptBuilder.php';
require_once __DIR__ . '/src/ValueObjects/Set.php';
require_once __DIR__ . '/src/ValueObjects/Enum.php';
require_once __DIR__ . '/src/ValueObjects/ValueObject.php';
require_once __DIR__ . '/src/ValueObjects/Config.php';
require_once __DIR__ . '/src/ValueObjects/GeneratorOptions.php';
require_once __DIR__ . '/src/ValueObjects/TargetMode.php';
require_once __DIR__ . '/src/commands/generate/ValueObjectBuilder.php';
require_once __DIR__ . '/src/commands/generate/EnumBuilder.php';
require_once __DIR__ . "/src/commands/generate/NullableEnumBuilder.php";
require_once __DIR__ . "/src/commands/generate/InterfaceBuilder.php";
require_once __DIR__ . '/src/commands/generate/SetBuilder.php';
require_once __DIR__ . '/src/commands/generate/GenerateCommand.php';
require_once __DIR__ . '/src/commands/fpp-convert/FppConvertCommand.php';
require_once __DIR__ . '/src/CommandHub.php';