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

use Checkout\Models\Address;

/**
 * Payment method Klarna.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class KlarnaSource extends Source
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
     * Magic Methods
     */

    /**
     * Initialise payment
     *
     * @param      string   $token     Klarna authentication token, obtained by the merchant during client transaction authorization.
     * @param      string  $currency  The currency in which the payment is being made (three-letter ISO 4217 code)
     * @param      string  $locale    The customer's locale (RFC 1766 code).
     * @param      Address  $billing   Customer's billing address.
     * @param      integer   $tax       Total tax amount of the order.
     * @param      Product[]    $products  This object is passed directly to Klarna as order_lines.
     */
    public function __construct($token, $country, $locale, Address $billing, $tax, array $products)
    {
        $this->type = static::MODEL_NAME;
        $this->authorization_token = $token;
        $this->billing_address = $billing;
        $this->purchase_country = $country;
        $this->locale = $locale;
        $this->tax_amount = $tax;
        $this->products = $products;
    }
}
