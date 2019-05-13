<?php

/**
 * Checkout.com
 * Authorised and regulated as an electronic money institution
 * by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * PHP version 7
 *
 * @category  SDK
 * @package   Checkout.com
 * @author    Platforms Development Team <platforms@checkout.com>
 * @copyright 2010-2019 Checkout.com
 * @license   https://opensource.org/licenses/mit-license.html MIT License
 * @link      https://docs.checkout.com/
 */

namespace Checkout\Library;

use function ctype_space;

/**
 * Class with set of utility functions.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Utilities
{

    /**
     * Get element from array safely.
     *
     * @param  array $arr
     * @param  type  $index
     * @param  mixed $default
     * @return mixed
     */
    public static function getValueFromArray(
        array &$arr,
              $index = 0,
              $default = null
    ) {
        $value = $default;

        if (isset($arr[$index])) {
            $value = $arr[$index];
        }

        return $value;
    }

    /**
     * Verify is value is empty.
     *
     * @note   Values that are considered empty: NULL;
     *                                         '' (empty string);
     *                                         array() (empty array);
     * @param  mixed $value
     * @return bool
     */
    public static function isEmpty(&$value)
    {
        return $value === null || $value === '' || (array) $value === array() ;
    }

    /**
     * Converts any type to array.
     *
     * @param  mixed $value
     * @return array array($value)
     */
    public static function toArray(&$value)
    {
        return is_array($value) ? $value : array($value);
    }

    /**
     * Get pointer for the first element of an array.
     *
     * @param  array $arr
     * @return pointer|null
     */
    public static function getFirstElementPointer(array &$arr)
    {
        $result = null;
        if (is_array($arr) && sizeof($arr)) {
            reset($arr);
            $result = &$arr[key($arr)];
        }

        return $result;
    }

    /**
     * Get pointer for the last element of an array.
     *
     * @param  array $arr
     * @return pointer|null
     */
    public static function getLastElementPointer(array &$arr)
    {
        $result = null;
        if (is_array($arr) && sizeof($arr)) {
            end($arr);
            $result = &$arr[key($arr)];
        }

        return $result;
    }

    /**
     * Load configuration file.
     *
     * @param  mixed $config
     * @return array
     */
    public static function loadConfig($config = array())
    {

        if (is_string($config) && is_readable($config)) {
            $config = parse_ini_file($config, true);
        }

        return $config;

    }

    /**
     * Convert string to camel case.
     *
     * @param  string $str
     * @return string
     */
    public static function toCamelCase($str)
    {
        return preg_replace_callback(
            '/(?:^\w|[A-Z]|\b\w|\s+)/',
            function ($m) {
                $c = $m[0];
                return ctype_space($c) ? '' : strtoupper($c);
            },
            $str
        );
    }
}
