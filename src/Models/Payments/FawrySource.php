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

use Checkout\Models\Product;

/**
 * Payment method Fawry.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class FawrySource extends Source
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
    const MODEL_NAME = 'fawry';


    /**
     * Magic Methods
     */

    /**
     * Initialise payment Fawry.
     *
     * @param      string       $email          The customer's email address.
     * @param      string       $mobile         The customer's mobile number.
     * @param      string       $description    The description of the payment.
     * @param      Product[]    $products       This object is passed directly to Fawry as products.
     */
    public function __construct($email, $mobile, $description, array $products)
    {
        $this->type = static::MODEL_NAME;
        $this->customer_email = $email;
        $this->customer_mobile = $mobile;
        $this->description = $description;
        $this->products = $products;
    }
}
