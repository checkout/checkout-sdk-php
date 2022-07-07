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
 * Payment method Oxxo.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class OxxoSource extends Source
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
    const MODEL_NAME = 'oxxo';

    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $integration_type;
    /**
     * @var string
     */
    public $country;
    /**
     * @var object
     */
    public $payer;
    /**
     * @var string
     */
    public $description;


    /**
     * Magic Methods
     */

    /**
     * Initialise Oxxo source.
     *
     * @param string $integrationType The type of integration. Either direct or redirect.
     * @param string $country The billing country.
     * @param object $payer The payer.
     * @param string $description A description of the order.
     */
    public function __construct($integrationType, $country, $payer, $description = '')
    {
        $this->type = static::MODEL_NAME;
        $this->integration_type = $integrationType;
        $this->country = $country;
        $this->payer = $payer;
        if ($description) {
            $this->description = $description;
        }
    }
}
