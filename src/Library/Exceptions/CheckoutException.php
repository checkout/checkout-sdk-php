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

namespace Checkout\Library\Exceptions;

use Checkout\Library\LogHandler;
use Exception;
use Throwable;

/**
 * Base exception class.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class CheckoutException extends Exception
{

    /**
     * Log exception.
     *
     * @param string    $message
     * @param int       $code
     * @param Throwable $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        if ($code) {
            $message .= ' (Code: ' . $code . ')';
        }

        LogHandler::error($message);
        parent::__construct($message, $code, $previous);
    }

}
