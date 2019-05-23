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

namespace Checkout\Models\Tokens;

use Checkout\Library\Model;
use Checkout\Library\Utilities;

/**
 * Card payment method.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 * @note     This class will be removed in the future. You MUST avoid using this class.
 */
class Card extends Token
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
     * Initialise source
     *
     * @param string $number
     * @param int    $month
     * @param int    $year
     */
    public function __construct($number, $month, $year)
    {
        $this->number = $number;
        $this->expiry_month = $month;
        $this->expiry_year = $year;
    }

    /**
     * Create response object.
     *
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {
        return new self(
            Utilities::getValueFromArray($response, 'number', ''),
            Utilities::getValueFromArray($response, 'expiry_month', 0),
            Utilities::getValueFromArray($response, 'expiry_year', 0)
        );
    }
}
