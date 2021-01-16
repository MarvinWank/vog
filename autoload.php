<?php

require_once __DIR__ . '/src/commands/generate/AbstractBuilder.php';
require_once __DIR__ . '/src/ValueObjects/Set.php';
require_once __DIR__ . '/src/ValueObjects/Enum.php';
require_once __DIR__ . '/src/ValueObjects/ValueObject.php';
require_once __DIR__ . '/src/ValueObjects/Config.php';
require_once __DIR__ . '/src/ValueObjects/GeneratorOptions.php';
require_once __DIR__ . '/src/ValueObjects/TargetMode.php';
require_once __DIR__ . '/src/commands/generate/ValueObjectBuilder.php';
require_once __DIR__ . '/src/commands/generate/EnumBuilder.php';
require_once __DIR__ . "/src/commands/generate/NullableEnumBuilder.php";
require_once __DIR__ . "/src/InterfaceBuilder.php";
require_once __DIR__ . '/src/commands/generate/SetBuilder.php';
require_once __DIR__ . '/src/commands/generate/Generate.php';
require_once __DIR__ . '/src/commands/fpp_convert/FppConvert.php';
require_once __DIR__ . '/src/CommandHub.php';

require_once __DIR__ . '/src/commands/fpp_convert/Set.php';
require_once __DIR__ . '/src/commands/fpp_convert/Enum.php';
require_once __DIR__ . '/src/commands/fpp_convert/ValueObject.php';