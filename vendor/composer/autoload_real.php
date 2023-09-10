<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitf8c9e971b34b113f01dcfe54d5d73cab
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitf8c9e971b34b113f01dcfe54d5d73cab', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitf8c9e971b34b113f01dcfe54d5d73cab', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitf8c9e971b34b113f01dcfe54d5d73cab::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
