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

namespace Checkout\Models\Sources;

use Checkout\Library\Model;
use Checkout\Library\Utilities;

/**
 * Model for sources.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Klarna extends Source
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
    const MODEL_NAME = 'klarna';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'klarna-external/credit-sessions';


    /**
     * Magic Methods
     */

    /**
     * Initialise source
     *
     * @param      string  $country   The country in which the purchase is being made (two-letter ISO 3166 code).
     * @param      string  $currency  The currency in which the payment is being made (three-letter ISO 4217 code)
     * @param      string  $locale    The customer's locale (RFC 1766 code).
     * @param      integer  $amount    The total amount of the order, including tax and any discounts.
     * @param      integer  $tax       The total tax amount of the order.
     * @param      Product[]   $products  The order details. This object is passed directly to Klarna as order_lines.
     */
    public function __construct($country, $currency, $locale, $amount, $tax, array $products)
    {
        $this->type = static::MODEL_NAME;
        $this->purchase_country = $country;
        $this->currency = $currency;
        $this->locale = $locale;
        $this->amount = $amount;
        $this->tax_amount = $tax;
        $this->products = $products;
    }

    /**
     * Factory.
     *
     * @note   Some classes will have to override this function.
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {
        return new self(
            Utilities::getValueFromArray($response, 'purchase_country', ''),
            Utilities::getValueFromArray($response, 'currency', ''),
            Utilities::getValueFromArray($response, 'locale', ''),
            Utilities::getValueFromArray($response, 'amount', 0),
            Utilities::getValueFromArray($response, 'tax_amount', 0),
            Utilities::getValueFromArray($response, 'products', array())
        );
    }


    /**
     * Setters and Getters
     */

    /**
     * Get ID if exists.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getValue('session_id');
    }

    /**
     * Get client token.
     *
     * @return string
     */
    public function getTokenId()
    {
        return $this->getValue('client_token');
    }

    /**
     * Utility function, cancel mandate.
     *
     * @return string
     */
    public function getPaymentMethods()
    {
        return $this->getValue('payment_method_categories');
    }

    /**
     * Get list of errors.
     *
     * @return array
     */
    public function getErrors()
    {
        $errors = $this->getValue('klarna_validation_errors');
        return $errors ? $errors : array();
    }

}
