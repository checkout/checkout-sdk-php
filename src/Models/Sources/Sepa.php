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
use Checkout\Models\Address;
use Checkout\Models\Payments\Customer;

/**
 * Model for sources.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Sepa extends Source
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
    const MODEL_NAME = 'sepa';


    /**
     * Magic Methods
     */

    /**
     * Initialise source
     *
     * @param BillingAddress $address
     * @param SepaData       $data
     * @param Customer|null  $customer
     */
    public function __construct(Address $address, SepaData $data, Customer $customer = null)
    {
        $this->type = static::MODEL_NAME;
        $this->billing_address = $address;
        $this->source_data = $data;
        if ($customer) {
            $this->customer = $customer;
        }
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
            new SepaAddress('', '', '', ''),
            new SepaData('', '', '', '', '', '')
        );
    }


    /**
     * Setters and Getters
     */

    /**
     * Utility function, cancel mandate.
     *
     * @return string
     */
    public function getSepaMandateCancel()
    {
        return $this->getLink('sepa:mandate-cancel');
    }

    /**
     * Utility function, get mandate.
     *
     * @return string
     */
    public function getSepaMandateGet()
    {
        return $this->getLink('sepa:mandate-get');
    }
}
