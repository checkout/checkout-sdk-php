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

namespace Checkout\Models\Payments;

/**
 * Payment method Przelewy24.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Przelewy24Source extends Source
{

    /**
     * Qualified name of the class.
     *
     * @var string
     */
    const QUALIFIED_NAME = __CLASS__;

    /**
     * Name of the model.
     *
     * @var string
     */
    const MODEL_NAME = 'p24';


    /**
     * Magic Methods
     */

    /**
     * Initialise Przelewy24.
     *
     * @param      string  $payment_country       The country of the payment (PL)
     * @param      string  $account_holder_name   The name of the account holder
     * @param      string  $account_holder_email  The email of the account holder
     * @param      string  $billing_descriptor    The billing descriptor
     */
    public function __construct($payment_country, $account_holder_name, $account_holder_email, $billing_descriptor = '')
    {
        $this->type = static::MODEL_NAME;
        $this->payment_country = $payment_country;
        $this->account_holder_name = $account_holder_name;
        $this->account_holder_email = $account_holder_email;
        $this->billing_descriptor = $billing_descriptor;
    }
}