<?php

require_once __DIR__. '/src/ConfigOptions.php';
require_once __DIR__ . '/src/commands/generate/AbstractBuilder.php';
require_once __DIR__ . '/src/commands/generate/ValueObjectBuilder.php';
require_once __DIR__ . '/src/commands/generate/EnumBuilder.php';
require_once __DIR__ . "/src/commands/generate/NullableEnumBuilder.php";
require_once __DIR__ . '/src/commands/generate/SetBuilder.php';
require_once __DIR__ . '/src/commands/generate/Generate.php';
require_once __DIR__ . '/src/commands/fpp_convert/FppConvert.php';
require_once __DIR__ . '/src/commands/fpp_convert/AbstractJsonObjectBuilder.php';
require_once __DIR__ . '/src/commands/fpp_convert/ObjectType.php';
require_once __DIR__ . '/src/commands/fpp_convert/ValueFileBuilder.php';
require_once __DIR__ . '/src/commands/fpp_convert/ValueObjectJsonObjectBuilder.php';
require_once __DIR__ . '/src/CommandHub.php';