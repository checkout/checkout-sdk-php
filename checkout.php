<?php

/**
 * Auto load Checkout SDK
 */
final class Checkout
{

    /**
     * @var string
     */
    const CHK_DIR = "Checkout";

    /**
     * Hold registration status
     *
     * @var bool
     */
    private static $registered = false;

    /**
     * Register autoload to
     */
    public static function register()
    {
        if (!static::$registered) {
            spl_autoload_register(array(__CLASS__, 'load'), true);
            static::$registered = true;
        }
    }

    /**
     * Load class
     *
     * @param  string $class
     * @return boolean
     */
    public static function load($class)
    {
        $file = static::find($class);
        if ($file) {
            include_once $file;
        }

        return (bool) $file;
    }

    /**
     * Find class file
     *
     * @param  string $class
     * @return string
     */
    public static function find($class)
    {
        $file = '';
        $arr = explode('\\', $class);

        if ($arr[0] === static::CHK_DIR) {
            $arr[0] = 'src';

            $file = __DIR__;
            foreach ($arr as &$value) {
                $file .= DIRECTORY_SEPARATOR . $value;
            }

            $file .= '.php';
            if (!file_exists($file)) {
                $file = '';
            }
        }

        return $file;
    }
}   Checkout::register();
