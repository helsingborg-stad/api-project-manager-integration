<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit071bf4436b735951177a9d010e4b5885
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
        'A' => 
        array (
            'AcfExportManager\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'AcfExportManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/helsingborg-stad/acf-export-manager/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit071bf4436b735951177a9d010e4b5885::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit071bf4436b735951177a9d010e4b5885::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}