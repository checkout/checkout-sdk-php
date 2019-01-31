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

/**
 * Checkout http exception.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class CheckoutHttpException extends CheckoutException
{

    /**
     * Body of HTTP response.
     *
     * @var mixed
     */
    protected $body;

    /**
     * List of errors returned by the API.
     *
     * @var array
     */
    protected $errors = array();


    /**
     * Setters and Getters
     */

    /**
     * Set body of HTTP response.
     *
     * @return mixed
     */
    public function setBody($body)
    {
        $this->body = $body;
        $obj = json_decode($body);
        if(isset($obj->error_codes)) {
            $this->errors = $obj->error_codes;
        }

        return $this;
    }

    /**
     * Get body of HTTP response.
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get list of errors from the exception.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
