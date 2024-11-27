<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1db0794924c044df9263fd0cc8ab2613
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\Src\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\Src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1db0794924c044df9263fd0cc8ab2613::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1db0794924c044df9263fd0cc8ab2613::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1db0794924c044df9263fd0cc8ab2613::$classMap;

        }, null, ClassLoader::class);
    }
}