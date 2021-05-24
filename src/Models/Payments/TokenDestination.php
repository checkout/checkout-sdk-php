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
 * Payment destination field model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class TokenDestination extends Destination
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
    const MODEL_NAME = 'token';


    /**
     * Magic Methods
     */

    /**
     * Initialise destination.
     *
     * @param string $token The Checkout.com token for example a card or wallet or token.
     * @param string $first The payment destination owner's first name.
     * @param string $last  The payment destination owner's last name.
     */
    public function __construct($token, $first, $last)
    {
        $this->type = static::MODEL_NAME;
        $this->token = $token;
        $this->first_name = $first;
        $this->last_name = $last;
    }
}
