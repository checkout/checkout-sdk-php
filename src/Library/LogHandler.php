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

/**
 * Log handler controller class.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class LogHandler
{

    /**
     * Error log path.
     *
     * @var string
     */
    public static $error;

    /**
     * Request log path.
     *
     * @var string
     */
    public static $request;

    /**
     * Respons log path.
     *
     * @var string
     */
    public static $response;


    /**
     * Methods
     */

    /**
     * Write to file.
     *
     * @param  string $path
     * @param  string $message
     * @return void
     */
    public static function write($path, $message)
    {
        if (static::$error) {
            file_put_contents($path, date(DATE_RFC2822) . ' - ' . $message . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * Add error log.
     *
     * @param  string $message
     * @return void
     */
    public static function error($message)
    {
        if (static::$error) {
            static::write(static::$error, $message);
        }
    }

    /**
     * Add request log.
     *
     * @param  string $message
     * @return void
     */
    public static function request($message)
    {
        if (static::$request) {
            static::write(static::$request, $message);
        }
    }

    /**
     * Add response log.
     *
     * @param  string $message
     * @return void
     */
    public static function response($message)
    {
        if (static::$response) {
            static::write(static::$response, $message);
        }
    }
}
