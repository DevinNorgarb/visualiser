<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4cdc29198e7c67c4036a09b19476a951
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '667aeda72477189d0494fecd327c3641' => __DIR__ . '/..' . '/symfony/var-dumper/Resources/functions/dump.php',
        'ace6d88241f812b4accb2d847454aef6' => __DIR__ . '/..' . '/halaxa/json-machine/src/functions.php',
        'fe62ba7e10580d903cc46d808b5961a4' => __DIR__ . '/..' . '/tightenco/collect/src/Collect/Support/helpers.php',
        'caf31cc6ec7cf2241cb6f12c226c3846' => __DIR__ . '/..' . '/tightenco/collect/src/Collect/Support/alias.php',
        '60799491728b879e74601d83e38b2cad' => __DIR__ . '/..' . '/illuminate/collections/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tightenco\\Collect\\' => 18,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\VarDumper\\' => 28,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Container\\' => 14,
            'Predis\\' => 7,
        ),
        'J' => 
        array (
            'JsonMachine\\' => 12,
        ),
        'I' => 
        array (
            'Illuminate\\Support\\' => 19,
            'Illuminate\\Contracts\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tightenco\\Collect\\' => 
        array (
            0 => __DIR__ . '/..' . '/tightenco/collect/src/Collect',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\VarDumper\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/var-dumper',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Predis\\' => 
        array (
            0 => __DIR__ . '/..' . '/predis/predis/src',
        ),
        'JsonMachine\\' => 
        array (
            0 => __DIR__ . '/..' . '/halaxa/json-machine/src',
        ),
        'Illuminate\\Support\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/macroable',
            1 => __DIR__ . '/..' . '/illuminate/collections',
        ),
        'Illuminate\\Contracts\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/contracts',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'TinyRedisClient' => __DIR__ . '/..' . '/ptrofimov/tinyredisclient/src/TinyRedisClient.php',
        'TinyRedisClient_Exception' => __DIR__ . '/..' . '/ptrofimov/tinyredisclient/src/TinyRedisClient.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4cdc29198e7c67c4036a09b19476a951::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4cdc29198e7c67c4036a09b19476a951::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4cdc29198e7c67c4036a09b19476a951::$classMap;

        }, null, ClassLoader::class);
    }
}
