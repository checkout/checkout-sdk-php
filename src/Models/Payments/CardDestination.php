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
class CardDestination extends Destination
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
    const MODEL_NAME = 'card';


    /**
     * Magic Methods
     */

    /**
     * Initialise destination.
     *
     * @param string $number    The card number.
     * @param int    $month     The two-digit expiry month of the card.
     * @param int    $year      The four-digit expiry year of the card.
     * @param string $first     The payment destination owner's first name.
     * @param string $last      The payment destination owner's last name.
     */
    public function __construct($number, $month, $year, $first, $last)
    {
        $this->type = static::MODEL_NAME;
        $this->number = $number;
        $this->expiry_month = $month;
        $this->expiry_year = $year;
        $this->first_name = $first;
        $this->last_name = $last;
    }
}
