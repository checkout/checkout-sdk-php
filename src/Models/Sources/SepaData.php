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

/**
 * Model for sources.add(sepa).source_data.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class SepaData extends Model
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
    const MODEL_NAME = 'source_data';


    /**
     * Magic Methods
     */

    /**
     * Initializes source data.
     *
     * @param string $first
     * @param string $last
     * @param string $iban
     * @param string $bic
     * @param string $descriptor
     * @param string $mandate
     */
    public function __construct($first, $last, $iban, $bic, $descriptor, $mandate)
    {
        $this->first_name = $first;
        $this->last_name = $last;
        $this->account_iban = $iban;
        $this->bic = $bic;
        $this->billing_descriptor = $descriptor;
        $this->mandate_type = $mandate;
    }
}
