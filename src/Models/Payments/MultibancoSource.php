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
 * Payment method Multibanco.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class MultibancoSource extends Source
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
    const MODEL_NAME = 'multibanco';


    /**
     * Magic Methods
     */

    /**
     * Initialise Multibanco source.
     *
     * @param string $integrationType   The type of integration. Either direct or redirect.
     * @param string $country           The billing country.
     * @param object $payer             The payer.
     * @param string $description       A description of the order.
     */
    public function __construct($country, $name, $descriptor = '')
    {
        $this->type = static::MODEL_NAME;
        $this->payment_country = $country;
        $this->account_holder_name = $name;
        $this->billing_descriptor = $descriptor;
    }
}
