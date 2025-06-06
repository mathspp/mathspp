<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaf03b1a7bf3346e5f474dda40236d9dd
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Grav\\Plugin\\NewsletterSubscriber\\' => 33,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Grav\\Plugin\\NewsletterSubscriber\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Grav\\Plugin\\NewsletterSubscriberPlugin' => __DIR__ . '/../..' . '/newsletter-subscriber.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaf03b1a7bf3346e5f474dda40236d9dd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaf03b1a7bf3346e5f474dda40236d9dd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitaf03b1a7bf3346e5f474dda40236d9dd::$classMap;

        }, null, ClassLoader::class);
    }
}
