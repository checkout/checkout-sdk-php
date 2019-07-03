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
 * Google payment method.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class GooglePay extends Token
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
    const MODEL_NAME = 'googlepay';


    /**
     * Magic Methods
     */

    /**
     * Initialise Google Pay.
     *
     * @param string $protocolVersion   Identifies which encryption/signing scheme this message has been created.
     *                                  In this way, the protocol can evolve over time if needed. If it is not set, assume ECv0.
     * @param string $signature         Verifies the message came from Google.
     *                                  The signature is created using ECDSA.
     * @param string $signedMessage     A serialized JSON string containing the encryptedMessage, ephemeralPublicKey and tag.
     *                                  To simplify the signature verification process, this value is serialized.
     */
    public function __construct($protocolVersion, $signature, $signedMessage)
    {
        $this->token_data = (object) array('protocolVersion'=> $protocolVersion,
                                           'signature'      => $signature,
                                           'signedMessage'  => $signedMessage);
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
            Utilities::getValueFromArray($response, 'protocolVersion', ''),
            Utilities::getValueFromArray($response, 'signature', ''),
            Utilities::getValueFromArray($response, 'signedMessage', '')
        );
    }
}
