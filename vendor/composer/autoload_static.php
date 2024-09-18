<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb0538d3672262fa798392e06b23e3243
{
    public static $files = array (
        '3109cb1a231dcd04bee1f9f620d46975' => __DIR__ . '/..' . '/paragonie/sodium_compat/autoload.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tavo\\' => 5,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tavo\\' => 
        array (
            0 => __DIR__ . '/..' . '/tavo1987/ec-validador-cedula-ruc/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb0538d3672262fa798392e06b23e3243::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb0538d3672262fa798392e06b23e3243::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb0538d3672262fa798392e06b23e3243::$classMap;

        }, null, ClassLoader::class);
    }
}
