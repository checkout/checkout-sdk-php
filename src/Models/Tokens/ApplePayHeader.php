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

/**
 * Apple Pay header.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class ApplePayHeader extends Model
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
    const MODEL_NAME = 'header';


    /**
     * Magic Methods
     */

    /**
     * Initilize Apple Pay Header.
     *
     * @param string $transactionId
     * @param string $publicKeyHash
     * @param string $ephemeralPublicKey
     */
    public function __construct($transactionId, $publicKeyHash, $ephemeralPublicKey)
    {
        $this->transactionId = $transactionId;
        $this->publicKeyHash = $publicKeyHash;
        $this->ephemeralPublicKey = $ephemeralPublicKey;
    }
}
