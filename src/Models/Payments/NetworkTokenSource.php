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
 * Payment method.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class NetworkTokenSource extends Source
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
    const MODEL_NAME = 'network_token';


    /**
     * Magic Methods
     */

    /**
     * Initialise source
     *
     * @param string $token
     * @param int    $month
     * @param int    $year
     * @param string $type
     */
    public function __construct($token, $month, $year, $type)
    {
        $this->type = static::MODEL_NAME;
        $this->token = $token;
        $this->expiry_month = $month;
        $this->expiry_year = $year;
        $this->token_type = $type;
    }
}
