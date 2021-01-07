<?php

namespace Vog;


class ConfigOptions
{
    const MODE_PSR2 = 'PSR2'; // generate PSR-2 compliant value objects
    const MODE_FPP = 'FPP'; // generate FPP compatible value objects

    const PHP_74 = 'PHP_74'; // enable generation of PHP 7.4 compliant value objects
    // currently disabled, as this requires a rewrite of the generator as well. It currently runs on 7.4 only
    //const PHP_71 = 'PHP_71'; // enable generation of PHP 7.1 complient value objects
}